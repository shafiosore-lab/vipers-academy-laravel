<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ModulePermission;
use App\Models\Organization;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\RoleAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrgRoleManagementController extends Controller
{
    /**
     * Constructor with organization middleware.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            // Allow both org-admin and super-admin roles
            if (!$user->hasRole('org-admin') && !$user->isSuperAdmin()) {
                abort(403, 'Access denied. Organization admin or Super admin role required.');
            }

            // For org-admin, check if they belong to an organization
            if (!$user->isSuperAdmin() && !$user->organization_id) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not belong to any organization.');
            }

            return $next($request);
        });
    }

    /**
     * Display role management for organization.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // For super-admin, allow selecting organization
        if ($user->isSuperAdmin()) {
            $organizations = Organization::active()->orderBy('name')->get();

            // Get selected organization from request or default to first
            $organizationId = $request->organization_id ?? $organizations->first()->id;
            $organization = Organization::findOrFail($organizationId);
        } else {
            $organization = $user->organization;
            $organizations = null;
        }

        // Get roles specific to this organization only
        $roles = Role::with(['permissions', 'parentRole'])
            ->where('organization_id', $organization->id)
            ->where('is_system', false)
            ->orderBy('name')
            ->paginate(20);

        // Get subscription plan and its limits
        $subscriptionPlan = $organization->subscriptionPlan;
        $maxRoles = $this->getMaxRoles($subscriptionPlan);
        $currentRoleCount = Role::where('organization_id', $organization->id)
            ->where('is_system', false)
            ->count();

        $stats = [
            'total_roles' => $currentRoleCount,
            'max_roles' => $maxRoles,
            'can_create_role' => $currentRoleCount < $maxRoles,
            'subscription_plan' => $subscriptionPlan ? $subscriptionPlan->name : 'No Plan',
        ];

        return view('organization.roles.index', compact('roles', 'stats', 'organization', 'organizations'));
    }

    /**
     * Show role creation form.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        // For super-admin, allow selecting organization
        if ($user->isSuperAdmin() && $request->has('organization_id')) {
            $organization = Organization::findOrFail($request->organization_id);
        } else {
            $organization = $user->organization;
        }

        $subscriptionPlan = $organization->subscriptionPlan;

        // Check role limit
        $currentRoleCount = Role::where('organization_id', $organization->id)
            ->where('is_system', false)
            ->count();

        $maxRoles = $this->getMaxRoles($subscriptionPlan);

        if ($currentRoleCount >= $maxRoles) {
            return redirect()->route('organization.roles.index')
                ->with('error', "Your subscription plan allows a maximum of {$maxRoles} roles. Please upgrade to create more.");
        }

        // Get permissions allowed by subscription
        $allowedPermissions = $this->getAllowedPermissions($subscriptionPlan);

        // Get available parent roles (only from this organization)
        $parentRoles = Role::where('organization_id', $organization->id)
            ->where('is_system', false)
            ->where('id', '!=', $user->id)
            ->get();

        // Get module permissions grouped by category
        $modules = ModulePermission::active()
            ->whereIn('id', $allowedPermissions['module_permission_ids'])
            ->get()
            ->groupBy('module');

        // Get all permissions with restriction info
        $permissions = Permission::whereIn('id', $allowedPermissions['permission_ids'])
            ->get()
            ->groupBy(function($perm) {
                return explode('.', $perm->slug)[0] ?? 'other';
            });

        return view('organization.roles.create', compact(
            'organization', 'parentRoles', 'modules', 'permissions', 'subscriptionPlan'
        ));
    }

    /**
     * Store new role.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // For super-admin, get organization from request
        if ($user->isSuperAdmin() && $request->has('organization_id')) {
            $organization = Organization::findOrFail($request->organization_id);
        } else {
            $organization = $user->organization;
        }

        if (!$organization) {
            return back()->with('error', 'No organization selected.');
        }

        $subscriptionPlan = $organization->subscriptionPlan;

        // Validate request
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                }),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                }),
            ],
            'description' => 'nullable|string',
            'parent_role_id' => 'nullable',
            'department' => 'nullable|string|max:100',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Get allowed permissions
        $allowedPermissions = $this->getAllowedPermissions($subscriptionPlan);

        // Backend validation: Check if all submitted permissions are allowed
        $requestedPermissionIds = $request->permissions;
        $forbiddenPermissions = array_diff($requestedPermissionIds, $allowedPermissions['permission_ids']);

        if (!empty($forbiddenPermissions)) {
            $forbiddenPerms = Permission::whereIn('id', $forbiddenPermissions)->pluck('name')->toArray();
            return back()->withErrors([
                'permissions' => 'You do not have access to the following permissions: ' . implode(', ', $forbiddenPerms)
            ])->withInput();
        }

        // Check parent role belongs to same organization
        if ($request->parent_role_id) {
            $parentRole = Role::find($request->parent_role_id);
            if (!$parentRole || $parentRole->organization_id != $organization->id) {
                return back()->withErrors([
                    'parent_role_id' => 'Invalid parent role selected.'
                ])->withInput();
            }
        }

        $role = DB::transaction(function () use ($request, $organization, $user) {
            $role = Role::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'type' => 'organization',
                'parent_role_id' => $request->parent_role_id,
                'inherit_permissions' => $request->boolean('inherit_permissions', true),
                'is_system' => false,
                'is_active' => true,
                'organization_id' => $organization->id,
                'department' => $request->department,
            ]);

            // Assign permissions with dependency enforcement
            $role->syncPermissionsWithDependencies($request->permissions);

            // Log role creation
            RoleAuditLog::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                'role_id' => $role->id,
                'action' => 'organization_role_created',
                'new_values' => [
                    'name' => $role->name,
                    'slug' => $role->slug,
                    'permissions_count' => count($request->permissions),
                ],
            ]);

            return $role;
        });

        return redirect()->route('organization.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display role details.
     */
    public function show(Role $role)
    {
        $user = auth()->user();

        // Get the organization this role belongs to
        $organization = Organization::find($role->organization_id);

        if (!$organization) {
            abort(404, 'Organization not found.');
        }

        // For non-super-admin, ensure role belongs to their organization
        if (!$user->isSuperAdmin() && $role->organization_id != $user->organization_id) {
            abort(403, 'Access denied. This role belongs to another organization.');
        }

        $role->load(['parentRole', 'childRoles', 'permissions']);

        // Get all permissions including inherited
        $allPermissions = $role->getAllPermissions();

        // Get audit logs for this role
        $auditLogs = RoleAuditLog::where('role_id', $role->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('organization.roles.show', compact('role', 'allPermissions', 'auditLogs', 'organization'));
    }

    /**
     * Show role edit form.
     */
    public function edit(Role $role)
    {
        $user = auth()->user();

        // Get the organization this role belongs to
        $organization = Organization::find($role->organization_id);

        if (!$organization) {
            abort(404, 'Organization not found.');
        }

        // For non-super-admin, ensure role belongs to their organization
        if (!$user->isSuperAdmin() && $role->organization_id != $user->organization_id) {
            abort(403, 'Access denied. This role belongs to another organization.');
        }

        $subscriptionPlan = $organization->subscriptionPlan;

        if ($role->is_system) {
            return redirect()->route('organization.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }

        // Get allowed permissions
        $allowedPermissions = $this->getAllowedPermissions($subscriptionPlan);

        // Get available parent roles
        $parentRoles = Role::where('organization_id', $organization->id)
            ->where('is_system', false)
            ->where('id', '!=', $role->id)
            ->get();

        // Get module permissions
        $modules = ModulePermission::active()
            ->whereIn('id', $allowedPermissions['module_permission_ids'])
            ->get()
            ->groupBy('module');

        // Get all permissions
        $permissions = Permission::whereIn('id', $allowedPermissions['permission_ids'])
            ->get()
            ->groupBy(function($perm) {
                return explode('.', $perm->slug)[0] ?? 'other';
            });

        return view('organization.roles.edit', compact(
            'role', 'parentRoles', 'modules', 'permissions', 'subscriptionPlan', 'organization'
        ));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role)
    {
        $user = auth()->user();

        // Get the organization this role belongs to
        $organization = Organization::find($role->organization_id);

        if (!$organization) {
            abort(404, 'Organization not found.');
        }

        // For non-super-admin, ensure role belongs to their organization
        if (!$user->isSuperAdmin() && $role->organization_id != $user->organization_id) {
            abort(403, 'Access denied. This role belongs to another organization.');
        }

        $subscriptionPlan = $organization->subscriptionPlan;

        if ($role->is_system) {
            return redirect()->route('organization.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($organization, $role) {
                    return $query->where('organization_id', $organization->id)->where('id', '!=', $role->id);
                }),
            ],
            'description' => 'nullable|string',
            'parent_role_id' => 'nullable',
            'department' => 'nullable|string|max:100',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Backend validation: Check if all submitted permissions are allowed
        $allowedPermissions = $this->getAllowedPermissions($subscriptionPlan);
        $requestedPermissionIds = $request->permissions;
        $forbiddenPermissions = array_diff($requestedPermissionIds, $allowedPermissions['permission_ids']);

        if (!empty($forbiddenPermissions)) {
            $forbiddenPerms = Permission::whereIn('id', $forbiddenPermissions)->pluck('name')->toArray();
            return back()->withErrors([
                'permissions' => 'You do not have access to the following permissions: ' . implode(', ', $forbiddenPerms)
            ])->withInput();
        }

        // Check parent role belongs to same organization
        if ($request->parent_role_id) {
            $parentRole = Role::find($request->parent_role_id);
            if (!$parentRole || $parentRole->organization_id != $organization->id) {
                return back()->withErrors([
                    'parent_role_id' => 'Invalid parent role selected.'
                ])->withInput();
            }
        }

        $oldPermissions = $role->permissions->pluck('id')->toArray();

        $role->update($request->only([
            'name', 'description', 'parent_role_id', 'inherit_permissions', 'department'
        ]));

        if ($request->has('permissions')) {
            $role->syncPermissionsWithDependencies($request->permissions);

            // Log permission changes
            RoleAuditLog::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                'role_id' => $role->id,
                'action' => 'organization_permission_changed',
                'old_values' => ['permissions' => $oldPermissions],
                'new_values' => ['permissions' => $request->permissions],
            ]);
        }

        return redirect()->route('organization.roles.show', $role)
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        $user = auth()->user();

        // Get the organization this role belongs to
        $organization = Organization::find($role->organization_id);

        if (!$organization) {
            abort(404, 'Organization not found.');
        }

        // For non-super-admin, ensure role belongs to their organization
        if (!$user->isSuperAdmin() && $role->organization_id != $user->organization_id) {
            abort(403, 'Access denied. This role belongs to another organization.');
        }

        if ($role->is_system) {
            return redirect()->route('organization.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        // Check if role has users assigned
        $usersWithRole = $role->users()->count();
        if ($usersWithRole > 0) {
            return redirect()->route('organization.roles.index')
                ->with('error', "Cannot delete role with {$usersWithRole} assigned users. Please reassign users first.");
        }

        // Check if role has child roles
        if ($role->childRoles()->count() > 0) {
            return redirect()->route('organization.roles.index')
                ->with('error', 'Cannot delete role with child roles. Please delete child roles first.');
        }

        // Log deletion
        RoleAuditLog::create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'role_id' => $role->id,
            'action' => 'organization_role_deleted',
            'old_values' => ['name' => $role->name, 'slug' => $role->slug],
        ]);

        $role->delete();

        return redirect()->route('organization.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Assign role to users within organization.
     */
    public function assignRole(Request $request, Role $role)
    {
        $user = auth()->user();
        $organization = $user->organization;

        // Ensure role belongs to same organization
        if ($role->organization_id != $organization->id) {
            abort(403, 'Access denied. This role belongs to another organization.');
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Verify all users belong to same organization
        $users = User::whereIn('id', $request->user_ids)
            ->where('organization_id', $organization->id)
            ->get();

        if ($users->count() !== count($request->user_ids)) {
            return back()->withError('Some selected users do not belong to your organization.');
        }

        // Check hierarchy: Can't assign roles higher than own level
        $userRoleLevel = $this->getRoleLevel($user);
        $targetRoleLevel = $this->getRoleLevel($role);

        if ($targetRoleLevel <= $userRoleLevel) {
            return back()->withError('You cannot assign a role at your level or higher.');
        }

        foreach ($users as $targetUser) {
            // Don't allow assigning to users at same or higher level
            $targetUserLevel = $this->getUserLevel($targetUser);
            if ($targetUserLevel <= $userRoleLevel) {
                continue;
            }

            $targetUser->assignRole($role);

            RoleAuditLog::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                'target_user_id' => $targetUser->id,
                'role_id' => $role->id,
                'action' => 'organization_role_assigned',
            ]);
        }

        return back()->with('success', 'Role assigned to selected users.');
    }

    /**
     * Get users who can be assigned this role.
     */
    public function getAssignableUsers(Role $role)
    {
        $user = auth()->user();
        $organization = $user->organization;

        if ($role->organization_id != $organization->id) {
            abort(403, 'Access denied.');
        }

        // Get users in organization excluding self and those with higher/equal roles
        $users = User::where('organization_id', $organization->id)
            ->where('id', '!=', $user->id)
            ->where('id', '!=', 1) // Exclude super admin
            ->get()
            ->filter(function($u) use ($user, $role) {
                $userLevel = $this->getUserLevel($user);
                $targetLevel = $this->getUserLevel($u);
                $roleLevel = $this->getRoleLevel($role);

                // Can only assign to users at lower levels
                return $targetLevel > $userLevel && $roleLevel > $userLevel;
            });

        return response()->json(['users' => $users]);
    }

    /**
     * Get role users.
     */
    public function getRoleUsers(Role $role)
    {
        $user = auth()->user();
        $organization = $user->organization;

        if ($role->organization_id != $organization->id) {
            abort(403, 'Access denied.');
        }

        $users = $role->users()
            ->where('organization_id', $organization->id)
            ->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Get permissions allowed by subscription.
     */
    protected function getAllowedPermissions(?SubscriptionPlan $plan): array
    {
        if (!$plan) {
            // No plan - return minimal permissions (just basic viewing)
            $basicPermissions = Permission::where('slug', 'like', '%.view')
                ->orWhere('slug', 'like', '%.read')
                ->get();

            return [
                'permission_ids' => $basicPermissions->pluck('id')->toArray(),
                'module_permission_ids' => [],
                'categories' => [],
            ];
        }

        // Get permission categories from plan features
        $features = $plan->features ?? [];
        $allowedCategories = $features['permission_categories'] ?? [];

        // If no categories defined, allow basic permissions
        if (empty($allowedCategories)) {
            $allowedCategories = ['players', 'staff', 'attendance', 'training'];
        }

        // Get module permissions for allowed categories
        $modulePermissions = ModulePermission::active()
            ->whereIn('module', $allowedCategories)
            ->get();

        // Get permissions for allowed modules
        $permissions = Permission::whereIn('module', $allowedCategories)->get();

        return [
            'permission_ids' => $permissions->pluck('id')->toArray(),
            'module_permission_ids' => $modulePermissions->pluck('id')->toArray(),
            'categories' => $allowedCategories,
        ];
    }

    /**
     * Get maximum roles allowed by subscription.
     */
    protected function getMaxRoles(?SubscriptionPlan $plan): int
    {
        if (!$plan) {
            return 3; // Default for no plan
        }

        return $plan->features['max_custom_roles'] ?? $plan->max_staff ?? 5;
    }

    /**
     * Get role level (higher = more privileges).
     */
    protected function getRoleLevel(Role $role): int
    {
        // Define role hierarchy levels
        $levels = [
            'super-admin' => 100,
            'org-owner' => 90,
            'org-admin' => 80,
            'manager' => 60,
            'coach' => 40,
            'staff' => 30,
            'player' => 10,
            'parent' => 20,
        ];

        return $levels[$role->slug] ?? 50;
    }

    /**
     * Get user's effective level.
     */
    protected function getUserLevel(User $user): int
    {
        if ($user->isSuperAdmin()) {
            return 100;
        }

        if ($user->isOrgAdmin()) {
            return 80;
        }

        // Get highest role level
        $maxLevel = 10; // Default lowest
        foreach ($user->roles as $role) {
            $level = $this->getRoleLevel($role);
            if ($level > $maxLevel) {
                $maxLevel = $level;
            }
        }

        return $maxLevel;
    }
}
