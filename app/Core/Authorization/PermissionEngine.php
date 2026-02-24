<?php

namespace App\Core\Authorization;

use App\Models\User;
use App\Models\Organization;
use App\Models\Role;
use App\Services\RoleHierarchyService;
use Illuminate\Support\Facades\Log;

/**
 * PermissionEngine - Centralized Authorization Service
 *
 * This class provides a unified interface for checking permissions across the system.
 * It enforces the hierarchy: Super Admin > Org Admin > Managers > Users
 *
 * @purpose: Centralize all authorization checks to prevent scattered, duplicate logic
 *           and ensure consistent hierarchy enforcement throughout the application.
 */
class PermissionEngine
{
    protected RoleHierarchyService $hierarchyService;

    public function __construct()
    {
        $this->hierarchyService = new RoleHierarchyService();
    }

    /**
     * Check if user can perform an action on a resource
     *
     * @param User $user The user attempting the action
     * @param string $action The action (view, create, edit, delete, etc.)
     * @param string|null $module The module/resource type (players, staff, etc.)
     * @param int|null $resourceId Optional resource ID for ownership check
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkPermission(
        User $user,
        string $action,
        ?string $module = null,
        ?int $resourceId = null
    ): array {
        // Super Admin bypasses all permission checks
        if ($user->hasRole('super-admin')) {
            Log::debug('PermissionEngine: Super admin bypass', [
                'user_id' => $user->id,
                'action' => $action,
                'module' => $module,
            ]);
            return ['allowed' => true, 'reason' => 'Super admin has full access'];
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return ['allowed' => false, 'reason' => 'User account is not approved'];
        }

        // Check basic permission for the module/action
        if ($module) {
            $permission = "{$module}.{$action}";

            if (!$user->hasPermission($permission)) {
                // Check if user has wildcard permission (e.g., players.*)
                if (!$user->hasPermission("{$module}.*")) {
                    Log::warning('PermissionEngine: Permission denied', [
                        'user_id' => $user->id,
                        'action' => $action,
                        'module' => $module,
                        'required' => $permission,
                    ]);
                    return ['allowed' => false, 'reason' => "Missing permission: {$permission}"];
                }
            }
        }

        return ['allowed' => true, 'reason' => 'Permission granted'];
    }

    /**
     * Check if user can access data within their organization scope
     *
     * @param User $user The user attempting access
     * @param Organization|null $resourceOrganization The organization of the resource
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkOrganizationScope(User $user, ?Organization $resourceOrganization = null): array
    {
        // Super Admin can access all organizations
        if ($user->hasRole('super-admin')) {
            return ['allowed' => true, 'reason' => 'Super admin bypasses organization scope'];
        }

        // If no resource organization, check if user has organization
        if (!$resourceOrganization) {
            if (!$user->organization_id) {
                return ['allowed' => false, 'reason' => 'User has no organization assigned'];
            }
            return ['allowed' => true, 'reason' => 'User accessing their own organization'];
        }

        // Org Admin can only access their organization
        if ($user->hasRole('org-admin')) {
            if ($user->organization_id !== $resourceOrganization->id) {
                Log::warning('PermissionEngine: Org admin cross-org access denied', [
                    'user_id' => $user->id,
                    'user_org' => $user->organization_id,
                    'resource_org' => $resourceOrganization->id,
                ]);
                return ['allowed' => false, 'reason' => 'Cannot access data from another organization'];
            }
            return ['allowed' => true, 'reason' => 'Org admin accessing their organization'];
        }

        // Staff/Manager roles - check organization match
        if ($user->organization_id !== $resourceOrganization->id) {
            Log::warning('PermissionEngine: Staff cross-org access denied', [
                'user_id' => $user->id,
                'user_org' => $user->organization_id,
                'resource_org' => $resourceOrganization->id,
            ]);
            return ['allowed' => false, 'reason' => 'Cannot access data from another organization'];
        }

        return ['allowed' => true, 'reason' => 'User accessing within their organization'];
    }

    /**
     * Check if user has sufficient role hierarchy to perform action
     *
     * @param User $user The user attempting the action
     * @param string $requiredRole The role slug required for this action
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkRoleHierarchy(User $user, string $requiredRole): array
    {
        // Super Admin bypasses all role requirements
        if ($user->hasRole('super-admin')) {
            return ['allowed' => true, 'reason' => 'Super admin has highest priority'];
        }

        // Get user's highest role priority
        $userRoles = $user->roles->pluck('slug')->toArray();
        $userHighestRole = $this->hierarchyService->getHighestPriorityRole($userRoles);
        $userPriority = $this->hierarchyService->hasHigherPriority($userHighestRole, $requiredRole)
            || $userHighestRole === $requiredRole;

        if (!$userPriority) {
            Log::warning('PermissionEngine: Insufficient role hierarchy', [
                'user_id' => $user->id,
                'user_roles' => $userRoles,
                'required_role' => $requiredRole,
                'highest_role' => $userHighestRole,
            ]);
            return ['allowed' => false, 'reason' => "Role '{$requiredRole}' or higher required"];
        }

        return ['allowed' => true, 'reason' => 'User has required role level'];
    }

    /**
     * Check if user can escalate privileges (assign roles)
     *
     * @param User $assigner The user assigning the role
     * @param string|Role $targetRole The role being assigned
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function canAssignRole(User $assigner, $targetRole): array
    {
        // Super Admin can assign any role
        if ($assigner->hasRole('super-admin')) {
            return ['allowed' => true, 'reason' => 'Super admin can assign any role'];
        }

        // Check using hierarchy service
        if ($this->hierarchyService->canUserEscalatePrivilege($assigner, $targetRole)) {
            return ['allowed' => true, 'reason' => 'User can assign this role'];
        }

        return ['allowed' => false, 'reason' => 'Cannot assign role - privilege escalation prevented'];
    }

    /**
     * Check if user can access a specific organization (for Super Admin functions)
     *
     * @param User $user The user attempting access
     * @param int $organizationId The organization ID to access
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function canAccessOrganization(User $user, int $organizationId): array
    {
        // Super Admin can access any organization
        if ($user->hasRole('super-admin')) {
            return ['allowed' => true, 'reason' => 'Super admin can access all organizations'];
        }

        // Other users can only access their own organization
        if ($user->organization_id !== $organizationId) {
            return ['allowed' => false, 'reason' => 'Can only access your own organization'];
        }

        return ['allowed' => true, 'reason' => 'User accessing their organization'];
    }

    /**
     * Get all permissions available to a user for a specific module
     *
     * @param User $user The user
     * @param string $module The module to get permissions for
     * @return array List of allowed actions
     */
    public function getModulePermissions(User $user, string $module): array
    {
        $permissions = $user->getAllPermissions();

        return $permissions
            ->filter(fn($p) => str_starts_with($p->slug, "{$module}."))
            ->map(fn($p) => str_replace("{$module}.", '', $p->slug))
            ->values()
            ->toArray();
    }

    /**
     * Validate complete access for a request (combines all checks)
     *
     * @param User $user The user making the request
     * @param string $action The action being performed
     * @param string $module The module/resource type
     * @param Organization|null $organization The organization context
     * @param string|null $requiredRole Optional role requirement
     * @return array ['allowed' => bool, 'reason' => string, 'checks' => array]
     */
    public function validateAccess(
        User $user,
        string $action,
        string $module,
        ?Organization $organization = null,
        ?string $requiredRole = null
    ): array {
        $checks = [];

        // Check 1: Permission
        $permissionCheck = $this->checkPermission($user, $action, $module);
        $checks['permission'] = $permissionCheck;

        // Check 2: Organization Scope (skip for super admin routes)
        if ($organization) {
            $scopeCheck = $this->checkOrganizationScope($user, $organization);
            $checks['organization_scope'] = $scopeCheck;
        }

        // Check 3: Role Hierarchy (if required)
        if ($requiredRole) {
            $roleCheck = $this->checkRoleHierarchy($user, $requiredRole);
            $checks['role_hierarchy'] = $roleCheck;
        }

        // Check 4: Account Status
        $statusCheck = [
            'allowed' => $user->isApproved(),
            'reason' => $user->isApproved() ? 'Account is approved' : 'Account pending approval'
        ];
        $checks['account_status'] = $statusCheck;

        // Determine final result
        $allowed = true;
        $reasons = [];

        foreach ($checks as $check) {
            if (!$check['allowed']) {
                $allowed = false;
                $reasons[] = $check['reason'];
            }
        }

        return [
            'allowed' => $allowed,
            'reason' => $allowed ? 'All checks passed' : implode('; ', $reasons),
            'checks' => $checks,
        ];
    }

    /**
     * Get the dashboard route for a user based on their highest role
     *
     * @param User $user
     * @return string
     */
    public function getDashboardRoute(User $user): string
    {
        return $this->hierarchyService->getDashboardRouteForUser($user);
    }

    /**
     * Check if a user can be assigned to a specific organization
     *
     * @param User $assigner The user doing the assignment
     * @param int $targetOrganizationId The organization to assign to
     * @return array
     */
    public function canAssignToOrganization(User $assigner, int $targetOrganizationId): array
    {
        // Super Admin can assign to any organization
        if ($assigner->hasRole('super-admin')) {
            return ['allowed' => true, 'reason' => 'Super admin can assign to any organization'];
        }

        // Org Admin can only assign to their organization
        if ($assigner->hasRole('org-admin')) {
            if ($assigner->organization_id !== $targetOrganizationId) {
                return ['allowed' => false, 'reason' => 'Can only assign users to your own organization'];
            }
            return ['allowed' => true, 'reason' => 'Assigning within organization'];
        }

        // Regular staff cannot assign organizations
        return ['allowed' => false, 'reason' => 'Not authorized to assign organizations'];
    }
}
