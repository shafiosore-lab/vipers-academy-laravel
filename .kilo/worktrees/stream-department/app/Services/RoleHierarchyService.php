<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class RoleHierarchyService
{
    /**
     * Base roles that are automatically assigned to all staff members
     */
    private const BASE_ROLE = 'staff-base';

    /**
     * Role hierarchy mapping (higher index = higher priority)
     */
    private const ROLE_HIERARCHY = [
        'staff-base' => 0,
        'player' => 1,
        'parent' => 2,
        'assistant-coach' => 3,
        'coach' => 4,
        'team-manager' => 5,
        'media-officer' => 6,
        'safeguarding-officer' => 7,
        'finance-officer' => 8,
        'head-coach' => 9,
        'admin-operations' => 10,
        'marketing-admin' => 11,
        'scouting-admin' => 12,
        'coaching-admin' => 13,
        'finance-admin' => 14,
        'operations-admin' => 15,
        'org-admin' => 50,
        'super-admin' => 100,
    ];

    /**
     * Dashboard route mapping for each role
     */
    private const DASHBOARD_ROUTES = [
        'super-admin' => 'admin.dashboard',
        'org-admin' => 'organization.dashboard',
        'marketing-admin' => 'admin.dashboard',
        'scouting-admin' => 'admin.dashboard',
        'coaching-admin' => 'admin.dashboard',
        'finance-admin' => 'admin.dashboard',
        'operations-admin' => 'admin.dashboard',
        'admin-operations' => 'admin.dashboard',
        'head-coach' => 'coach.dashboard',
        'assistant-coach' => 'coach.dashboard',
        'coach' => 'coach.dashboard',
        'team-manager' => 'manager.dashboard',
        'media-officer' => 'media.dashboard',
        'safeguarding-officer' => 'welfare.dashboard',
        'finance-officer' => 'finance.dashboard',
        'partner-marketing' => 'partner.dashboard',
        'partner-scouting' => 'partner.dashboard',
        'partner-operations' => 'partner.dashboard',
        'player' => 'player.portal.dashboard',
        'parent' => 'parent.dashboard',
        'staff-base' => 'coach.dashboard', // Default to coach dashboard
    ];

    /**
     * Module groupings for roles
     */
    private const ROLE_MODULES = [
        'super-admin' => 'platform_administration',
        'org-admin' => 'organization_administration',
        'marketing-admin' => 'admin_operations',
        'scouting-admin' => 'admin_operations',
        'coaching-admin' => 'admin_operations',
        'finance-admin' => 'admin_operations',
        'operations-admin' => 'admin_operations',
        'admin-operations' => 'admin_operations',
        'head-coach' => 'coaching',
        'assistant-coach' => 'coaching',
        'coach' => 'coaching',
        'team-manager' => 'management',
        'media-officer' => 'media',
        'safeguarding-officer' => 'welfare',
        'finance-officer' => 'finance',
    ];

    /**
     * Get the base role for all staff members
     */
    public function getBaseRole(): ?Role
    {
        return Role::where('slug', self::BASE_ROLE)->first();
    }

    /**
     * Ensure base role exists and create it if it doesn't
     */
    public function ensureBaseRoleExists(): Role
    {
        return Role::updateOrCreate(
            ['slug' => self::BASE_ROLE],
            [
                'name' => 'Staff Base',
                'description' => 'Base role for all staff members - inherits permissions to specialized roles',
                'type' => 'partner_staff',
                'is_default' => false,
                'level' => 0,
                'module' => 'base',
                'is_subscription_restricted' => false,
                'min_plan_level' => 0,
            ]
        );
    }

    /**
     * Assign base role to a user along with their specialized roles
     */
    public function assignBaseRoleWithSpecialization(User $user, array $roleSlugs): void
    {
        // Ensure base role exists
        $baseRole = $this->ensureBaseRoleExists();

        // Assign base role if not already assigned
        if (!$user->hasRole(self::BASE_ROLE)) {
            $user->assignRole($baseRole);
        }

        // Assign specialized roles
        foreach ($roleSlugs as $roleSlug) {
            $role = Role::where('slug', $roleSlug)->first();
            if ($role && !$user->hasRole($roleSlug)) {
                $user->assignRole($role);
            }
        }
    }

    /**
     * Get the dashboard route for a user based on their highest role
     */
    public function getDashboardRouteForUser(User $user): string
    {
        $userRoles = $user->roles->pluck('slug')->toArray();

        // Find the highest priority role
        $highestRole = $this->getHighestPriorityRole($userRoles);

        return self::DASHBOARD_ROUTES[$highestRole] ?? 'home';
    }

    /**
     * Get the highest priority role from a list of roles
     */
    public function getHighestPriorityRole(array $roleSlugs): string
    {
        $highestRole = null;
        $highestPriority = -1;

        foreach ($roleSlugs as $role) {
            $priority = self::ROLE_HIERARCHY[$role] ?? 0;
            if ($priority > $highestPriority) {
                $highestPriority = $priority;
                $highestRole = $role;
            }
        }

        return $highestRole ?? 'staff-base';
    }

    /**
     * Check if a role has higher priority than another
     */
    public function hasHigherPriority(string $role1, string $role2): bool
    {
        $priority1 = self::ROLE_HIERARCHY[$role1] ?? 0;
        $priority2 = self::ROLE_HIERARCHY[$role2] ?? 0;
        return $priority1 > $priority2;
    }

    /**
     * Get all roles that inherit from a base role
     */
    public function getInheritedRoles(string $baseRole): array
    {
        $basePriority = self::ROLE_HIERARCHY[$baseRole] ?? 0;

        return array_filter(
            self::ROLE_HIERARCHY,
            fn($priority) => $priority >= $basePriority && $priority < self::ROLE_HIERARCHY['super-admin']
        );
    }

    /**
     * Get role info including dashboard route and permissions
     */
    public function getRoleInfo(string $roleSlug): array
    {
        $role = Role::where('slug', $roleSlug)->first();

        return [
            'role' => $role,
            'dashboard_route' => self::DASHBOARD_ROUTES[$roleSlug] ?? 'home',
            'priority' => self::ROLE_HIERARCHY[$roleSlug] ?? 0,
            'is_admin' => in_array($roleSlug, ['super-admin', 'marketing-admin', 'scouting-admin', 'coaching-admin', 'finance-admin', 'operations-admin', 'admin-operations']),
            'is_staff' => in_array($roleSlug, ['head-coach', 'assistant-coach', 'coach', 'team-manager', 'media-officer', 'safeguarding-officer', 'finance-officer']),
            'is_partner' => str_starts_with($roleSlug, 'partner-'),
            'module' => self::ROLE_MODULES[$roleSlug] ?? 'other',
        ];
    }

    /**
     * Get all available dashboard routes
     */
    public function getAllDashboardRoutes(): array
    {
        return [
            'admin.dashboard' => 'Admin Dashboard',
            'coach.dashboard' => 'Coach Dashboard',
            'manager.dashboard' => 'Team Manager Dashboard',
            'media.dashboard' => 'Media Officer Dashboard',
            'welfare.dashboard' => 'Welfare Officer Dashboard',
            'finance.dashboard' => 'Finance Officer Dashboard',
            'partner.dashboard' => 'Partner Dashboard',
            'player.portal.dashboard' => 'Player Portal',
            'parent.dashboard' => 'Parent Portal',
        ];
    }

    /**
     * Get roles that should access a specific dashboard
     */
    public function getRolesForDashboard(string $dashboardRoute): array
    {
        return array_filter(
            self::DASHBOARD_ROUTES,
            fn($route) => $route === $dashboardRoute
        );
    }

    /**
     * =====================================================================
     * SUBSCRIPTION-BASED ROLE FILTERING
     * =====================================================================
     */

    /**
     * Get all roles available for a specific user based on their permission level and subscription.
     *
     * For Super Admin: Returns all roles in the system
     * For Org Admin: Returns only roles within their subscription limits
     *
     * @param User $user The user who is creating/assigning roles
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableRolesForUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        // Super admin has access to all roles
        if ($user->hasRole('super-admin')) {
            Log::info('RoleHierarchyService: Super admin accessing all roles');
            return Role::orderBy('name')->get();
        }

        // Org admin has limited access based on their subscription
        if ($user->hasRole('org-admin')) {
            Log::info('RoleHierarchyService: Org admin accessing subscription-limited roles');
            return $this->getRolesForOrgAdmin($user);
        }

        // Partner-level staff (created by org admin) gets limited roles
        if ($user->hasRole('partner') || $user->isPartner()) {
            return $this->getRolesForPartner($user);
        }

        // Default: return all non-restricted roles
        return Role::where('is_subscription_restricted', false)->orderBy('name')->get();
    }

    /**
     * Get roles available for an Organization Admin based on their subscription plan.
     *
     * @param User $orgAdmin The organization admin user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesForOrgAdmin(User $orgAdmin): \Illuminate\Database\Eloquent\Collection
    {
        // Get the organization the admin belongs to
        $organization = $this->getOrganizationForUser($orgAdmin);

        if (!$organization) {
            Log::warning('RoleHierarchyService: Org admin without organization', ['user_id' => $orgAdmin->id]);
            // Return basic staff roles if no organization found
            return Role::whereIn('slug', [
                'staff-base',
                'coach',
                'assistant-coach',
                'team-manager',
            ])->get();
        }

        // Get the subscription plan
        $subscriptionPlan = $organization->subscriptionPlan;

        if (!$subscriptionPlan) {
            Log::warning('RoleHierarchyService: Org admin without subscription plan', [
                'user_id' => $orgAdmin->id,
                'organization_id' => $organization->id
            ]);
            // Return basic roles for users without subscription
            return Role::whereIn('slug', [
                'staff-base',
                'coach',
                'assistant-coach',
                'team-manager',
            ])->get();
        }

        Log::info('RoleHierarchyService: Filtering roles by subscription plan', [
            'user_id' => $orgAdmin->id,
            'plan_slug' => $subscriptionPlan->slug,
            'plan_name' => $subscriptionPlan->name,
        ]);

        // Get roles accessible for this subscription plan
        return $this->getRolesAccessibleForPlan($subscriptionPlan, $organization);
    }

    /**
     * Get roles available for a Partner user based on their parent org's subscription.
     *
     * @param User $partner The partner user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesForPartner(User $partner): \Illuminate\Database\Eloquent\Collection
    {
        // Get the partner's organization
        $organization = $this->getOrganizationForUser($partner);

        if (!$organization) {
            // Return minimal roles for partners without org
            return Role::whereIn('slug', [
                'staff-base',
                'coach',
                'assistant-coach',
            ])->get();
        }

        // Partners get a subset of org admin roles (limited)
        $subscriptionPlan = $organization->subscriptionPlan;

        if (!$subscriptionPlan) {
            return Role::whereIn('slug', [
                'staff-base',
                'coach',
                'assistant-coach',
            ])->get();
        }

        // Partners get fewer roles than org admin
        return Role::whereIn('slug', [
            'staff-base',
            'coach',
            'assistant-coach',
            'team-manager',
        ])->where(function ($query) use ($subscriptionPlan) {
            $query->where('is_subscription_restricted', false)
                  ->orWhere('min_plan_level', '<=', $this->getPlanOrder($subscriptionPlan->slug));
        })->get();
    }

    /**
     * Get roles accessible for a specific subscription plan.
     *
     * @param SubscriptionPlan $plan The subscription plan
     * @param Organization $organization The organization
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesAccessibleForPlan(SubscriptionPlan $plan, Organization $organization): \Illuminate\Database\Eloquent\Collection
    {
        $planOrder = $this->getPlanOrder($plan->slug);
        $planFeatures = $plan->features ?? [];

        return Role::where(function ($query) use ($plan, $planOrder, $planFeatures) {
            $query->where('is_subscription_restricted', false)
                  ->orWhere(function ($q) use ($plan, $planOrder) {
                      // Role not restricted OR
                      $q->where('is_subscription_restricted', true)
                        ->where(function ($q2) use ($planOrder) {
                            // Has lower or no min plan level requirement
                            $q2->where('min_plan_level', '<=', $planOrder)
                               ->orWhere('min_plan_level', 0)
                               ->orWhereNull('min_plan_level');
                        });
                  });
        })->orderBy('name')->get();
    }

    /**
     * Check if a specific role can be assigned to a user based on their permission level.
     *
     * @param User $creator The user who is assigning the role
     * @param Role $role The role to check
     * @return bool
     */
    public function canAssignRole(User $creator, Role $role): bool
    {
        // Super admin can assign any role
        if ($creator->hasRole('super-admin')) {
            return true;
        }

        // Org admin can only assign roles within their subscription
        if ($creator->hasRole('org-admin')) {
            $availableRoles = $this->getRolesForOrgAdmin($creator);
            return $availableRoles->contains('id', $role->id);
        }

        // Partner can only assign limited roles
        if ($creator->hasRole('partner') || $creator->isPartner()) {
            $availableRoles = $this->getRolesForPartner($creator);
            return $availableRoles->contains('id', $role->id);
        }

        // Prevent privilege escalation - only allow base role for regular staff
        return in_array($role->slug, ['staff-base']);
    }

    /**
     * Get plan order for comparison (higher = more premium).
     *
     * @param string $planSlug
     * @return int
     */
    public function getPlanOrder(string $planSlug): int
    {
        return match($planSlug) {
            'starter' => 1,
            'professional' => 2,
            'enterprise' => 3,
            default => 0,
        };
    }

    /**
     * Get the organization for a user (supports both org-admin and partner).
     *
     * @param User $user
     * @return Organization|null
     */
    public function getOrganizationForUser(User $user): ?Organization
    {
        // Check if user has organization_id
        if ($user->organization_id) {
            return Organization::find($user->organization_id);
        }

        // For partners, check partner relationship
        if ($user->partner) {
            return $user->partner->organization ?? null;
        }

        // Check if user is an org admin
        if ($user->hasRole('org-admin')) {
            return Organization::where('created_by', $user->id)->first();
        }

        return null;
    }

    /**
     * Get roles filtered by module/category.
     *
     * @param string $module
     * @param User|null $user Optional user to filter by their permissions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesByModule(string $module, ?User $user = null): \Illuminate\Database\Eloquent\Collection
    {
        $roles = Role::where('module', $module)->orderBy('name');

        if ($user) {
            $availableRoleIds = $this->getAvailableRolesForUser($user)->pluck('id');
            $roles->whereIn('id', $availableRoleIds);
        }

        return $roles->get();
    }

    /**
     * Get all modules with available roles for a user.
     *
     * @param User $user
     * @return array
     */
    public function getAvailableModulesForUser(User $user): array
    {
        $availableRoles = $this->getAvailableRolesForUser($user);

        $modules = $availableRoles->pluck('module')->filter()->unique()->values()->toArray();

        // Add default modules if none found
        if (empty($modules)) {
            return ['coaching', 'management', 'admin_operations'];
        }

        return $modules;
    }

    /**
     * Validate role assignment to prevent privilege escalation.
     *
     * @param User $creator The user creating the staff member
     * @param string|Role $targetRole The role to be assigned
     * @return bool
     */
    public function canUserEscalatePrivilege(User $creator, string|Role $targetRole): bool
    {
        $targetRoleSlug = $targetRole instanceof Role ? $targetRole->slug : $targetRole;
        $targetPriority = self::ROLE_HIERARCHY[$targetRoleSlug] ?? 0;

        // Get creator's highest role priority
        $creatorRoles = $creator->roles->pluck('slug')->toArray();
        $creatorHighestPriority = 0;

        foreach ($creatorRoles as $roleSlug) {
            $priority = self::ROLE_HIERARCHY[$roleSlug] ?? 0;
            if ($priority > $creatorHighestPriority) {
                $creatorHighestPriority = $priority;
            }
        }

        // Prevent assigning roles with higher priority than creator's highest role
        return $targetPriority <= $creatorHighestPriority;
    }

    /**
     * Get permission sets available for a user.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailablePermissionsForUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        // Get all roles available to this user
        $availableRoles = $this->getAvailableRolesForUser($user);

        // Get all unique permissions from those roles
        $permissionIds = [];
        foreach ($availableRoles as $role) {
            $permissions = $role->permissions->pluck('id')->toArray();
            $permissionIds = array_merge($permissionIds, $permissions);
        }

        // Return unique permissions
        return \App\Models\Permission::whereIn('id', array_unique($permissionIds))->get();
    }

    /**
     * Get role creation context info for audit logging.
     *
     * @param User $creator
     * @return array
     */
    public function getRoleCreationContext(User $creator): array
    {
        $context = [
            'user_id' => $creator->id,
            'user_email' => $creator->email,
            'is_super_admin' => $creator->hasRole('super-admin'),
            'is_org_admin' => $creator->hasRole('org-admin'),
        ];

        $organization = $this->getOrganizationForUser($creator);
        if ($organization) {
            $context['organization_id'] = $organization->id;
            $context['organization_name'] = $organization->name;

            if ($organization->subscriptionPlan) {
                $context['subscription_plan'] = $organization->subscriptionPlan->name;
                $context['subscription_slug'] = $organization->subscriptionPlan->slug;
            }
        }

        $availableRoles = $this->getAvailableRolesForUser($creator);
        $context['available_role_count'] = $availableRoles->count();
        $context['available_role_slugs'] = $availableRoles->pluck('slug')->toArray();

        return $context;
    }
}
