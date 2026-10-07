<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleTemplate;
use App\Models\RoleRequest;
use App\Models\RoleAuditLog;
use App\Models\ModulePermission;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleManagementController extends Controller
{
    /**
     * Display role management dashboard.
     */
    public function index()
    {
        $roles = Role::with(['organization', 'parentRole'])
            ->orderBy('name')
            ->paginate(20);

        $stats = [
            'total_roles' => Role::count(),
            'system_roles' => Role::where('is_system', true)->count(),
            'template_roles' => Role::where('is_template', true)->count(),
            'org_specific_roles' => Role::whereNotNull('organization_id')->count(),
            'pending_requests' => RoleRequest::where('status', 'pending')->count(),
        ];

        return view('super-admin.roles.index', compact('roles', 'stats'));
    }

    /**
     * Display role hierarchy tree.
     */
    public function hierarchy()
    {
        $roles = Role::with(['parentRole', 'childRoles', 'permissions'])
            ->whereNull('organization_id')
            ->orWhere('is_template', true)
            ->get();

        $hierarchy = $this->buildRoleHierarchy($roles);

        return view('super-admin.roles.hierarchy', compact('hierarchy'));
    }

    /**
     * Build nested hierarchy tree.
     */
    protected function buildRoleHierarchy($roles, $parentId = null): array
    {
        $tree = [];

        foreach ($roles as $role) {
            if ($role->parent_role_id === $parentId) {
                $children = $this->buildRoleHierarchy($roles, $role->id);

                $tree[] = [
                    'role' => $role,
                    'children' => $children,
                    'permissions' => $role->permissions,
                    'depth' => $this->getRoleDepth($roles, $role->id),
                ];
            }
        }

        return $tree;
    }

    /**
     * Get role depth in hierarchy.
     */
    protected function getRoleDepth($roles, $roleId, $depth = 0): int
    {
        foreach ($roles as $role) {
            if ($role->id === $roleId && $role->parent_role_id) {
                return $this->getRoleDepth($roles, $role->parent_role_id, $depth + 1);
            }
        }
        return $depth;
    }

    /**
     * Show role creation form.
     */
    public function create()
    {
        $roles = Role::whereNull('organization_id')
            ->orWhere('is_template', true)
            ->get();

        $modules = ModulePermission::active()->get()->groupBy('module');

        return view('super-admin.roles.create', compact('roles', 'modules'));
    }

    /**
     * Store new role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string',
            'parent_role_id' => 'nullable|exists:roles,id',
            'department' => 'nullable|string|max:100',
            'module' => 'nullable|string|max:100',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'is_template' => 'boolean',
            'is_system' => 'boolean',
            'inherit_permissions' => 'boolean',
        ]);

        $role = DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'name_key' => $request->name_key,
                'description_key' => $request->description_key,
                'type' => $request->type ?? 'custom',
                'parent_role_id' => $request->parent_role_id,
                'inherit_permissions' => $request->boolean('inherit_permissions', true),
                'is_template' => $request->boolean('is_template'),
                'is_system' => $request->boolean('is_system'),
                'department' => $request->department,
                'module' => $request->module,
                'is_active' => true,
                'organization_id' => $request->organization_id,
            ]);

            // Assign permissions with dependency enforcement
            if ($request->has('permissions')) {
                $role->syncPermissionsWithDependencies($request->permissions);
            }

            // Log role creation
            RoleAuditLog::logRoleCreated(auth()->user(), $role);

            return $role;
        });

        return redirect()->route('super-admin.roles.show', $role)
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display role details.
     */
    public function show(Role $role)
    {
        $role->load(['parentRole', 'childRoles', 'permissions', 'organization']);

        // Get inherited permissions if applicable
        $allPermissions = $role->getAllPermissions();

        // Get audit logs for this role
        $auditLogs = RoleAuditLog::where('role_id', $role->id)
            ->with(['user', 'targetUser'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('super-admin.roles.show', compact('role', 'allPermissions', 'auditLogs'));
    }

    /**
     * Show role edit form.
     */
    public function edit(Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('super-admin.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }

        $roles = Role::whereNull('organization_id')
            ->where('id', '!=', $role->id)
            ->get();

        $modules = ModulePermission::active()->get()->groupBy('module');
        $allPermissions = Permission::all();

        return view('super-admin.roles.edit', compact('role', 'roles', 'modules', 'allPermissions'));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('super-admin.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'parent_role_id' => 'nullable|exists:roles,id',
            'department' => 'nullable|string|max:100',
            'module' => 'nullable|string|max:100',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $oldPermissions = $role->permissions->pluck('id')->toArray();

        $role->update($request->only([
            'name', 'description', 'name_key', 'description_key',
            'parent_role_id', 'inherit_permissions', 'department', 'module'
        ]));

        if ($request->has('permissions')) {
            $role->syncPermissionsWithDependencies($request->permissions);

            // Log permission changes
            RoleAuditLog::logPermissionChange(
                auth()->user(),
                $role,
                $oldPermissions,
                $request->permissions
            );
        }

        return redirect()->route('super-admin.roles.show', $role)
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('super-admin.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('super-admin.roles.index')
                ->with('error', 'Cannot delete role with assigned users. Please reassign users first.');
        }

        if ($role->childRoles()->count() > 0) {
            return redirect()->route('super-admin.roles.index')
                ->with('error', 'Cannot delete role with child roles. Please delete child roles first.');
        }

        // Log deletion
        RoleAuditLog::create([
            'user_id' => auth()->id(),
            'role_id' => $role->id,
            'action' => RoleAuditLog::ACTION_ROLE_DELETED,
            'old_values' => ['name' => $role->name, 'slug' => $role->slug],
        ]);

        $role->delete();

        return redirect()->route('super-admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Display role templates.
     */
    public function templates()
    {
        $templates = RoleTemplate::with(['creator', 'organization'])
            ->orderBy('name')
            ->paginate(20);

        return view('super-admin.roles.templates.index', compact('templates'));
    }

    /**
     * Create role template.
     */
    public function createTemplate()
    {
        $roles = Role::all();

        return view('super-admin.roles.templates.create', compact('roles'));
    }

    /**
     * Store role template.
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:role_templates,slug',
            'description' => 'nullable|string',
            'role_configurations' => 'required|array',
        ]);

        RoleTemplate::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'organization_id' => $request->organization_id,
            'role_configurations' => $request->role_configurations,
            'is_public' => $request->boolean('is_public'),
            'is_active' => true,
        ]);

        return redirect()->route('super-admin.roles.templates.index')
            ->with('success', 'Role template created successfully.');
    }

    /**
     * Display role requests.
     */
    public function requests()
    {
        $requests = RoleRequest::with(['user', 'requestedRole', 'organization'])
            ->orderBy('requested_at', 'desc')
            ->paginate(20);

        return view('super-admin.roles.requests.index', compact('requests'));
    }

    /**
     * Approve role request.
     */
    public function approveRequest(RoleRequest $request)
    {
        $request->approve(auth()->user(), request('notes'));

        return redirect()->back()
            ->with('success', 'Role request approved.');
    }

    /**
     * Reject role request.
     */
    public function rejectRequest(RoleRequest $request)
    {
        $request->reject(auth()->user(), request('notes'));

        return redirect()->back()
            ->with('success', 'Role request rejected.');
    }

    /**
     * Display audit logs.
     */
    public function auditLogs(Request $request)
    {
        $query = RoleAuditLog::with(['user', 'targetUser', 'role', 'organization']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('target_user_id')) {
            $query->where('target_user_id', $request->target_user_id);
        }

        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        $actions = [
            RoleAuditLog::ACTION_ROLE_ASSIGNED,
            RoleAuditLog::ACTION_ROLE_REMOVED,
            RoleAuditLog::ACTION_PERMISSION_CHANGED,
            RoleAuditLog::ACTION_ROLE_CREATED,
            RoleAuditLog::ACTION_ROLE_DELETED,
            RoleAuditLog::ACTION_ROLE_REQUESTED,
        ];

        return view('super-admin.roles.audit', compact('logs', 'actions'));
    }

    /**
     * Create hybrid role (combining multiple roles).
     */
    public function createHybrid(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string',
            'base_roles' => 'required|array|min:1',
            'base_roles.*' => 'exists:roles,id',
            'additional_permissions' => 'array',
            'additional_permissions.*' => 'exists:permissions,id',
        ]);

        $role = DB::transaction(function () use ($request) {
            // Get all permissions from base roles
            $basePermissions = [];
            $combinedRoleIds = [];

            foreach ($request->base_roles as $roleId) {
                $baseRole = Role::find($roleId);
                $combinedRoleIds[] = $roleId;

                // Get direct permissions
                $permissions = $baseRole->permissions->pluck('id')->toArray();
                $basePermissions = array_merge($basePermissions, $permissions);
            }

            // Add additional permissions
            if ($request->has('additional_permissions')) {
                $basePermissions = array_merge($basePermissions, $request->additional_permissions);
            }

            $role = Role::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'type' => 'hybrid',
                'combined_role_ids' => implode(',', $combinedRoleIds),
                'is_active' => true,
                'is_system' => false,
            ]);

            $role->permissions()->sync(array_unique($basePermissions));

            // Log hybrid role creation
            RoleAuditLog::create([
                'user_id' => auth()->id(),
                'role_id' => $role->id,
                'action' => RoleAuditLog::ACTION_HYBRID_ROLE_CREATED,
                'new_values' => [
                    'name' => $role->name,
                    'combined_roles' => $combinedRoleIds,
                ],
            ]);

            return $role;
        });

        return redirect()->route('super-admin.roles.show', $role)
            ->with('success', 'Hybrid role created successfully.');
    }

    /**
     * Display module permissions management.
     */
    public function modulePermissions()
    {
        $modules = ModulePermission::orderBy('module')->get();

        return view('super-admin.roles.module-permissions.index', compact('modules'));
    }

    /**
     * Create module permission.
     */
    public function storeModulePermission(Request $request)
    {
        $request->validate([
            'module' => 'required|string|unique:module_permissions,module',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actions' => 'required|array',
        ]);

        ModulePermission::create($request->all());

        return redirect()->back()
            ->with('success', 'Module permission created successfully.');
    }

    /**
     * Get role tree for visualization (API).
     */
    public function getRoleTree()
    {
        $roles = Role::with(['parentRole', 'childRoles', 'permissions'])
            ->whereNull('organization_id')
            ->orWhere('is_template', true)
            ->get();

        return response()->json([
            'hierarchy' => $this->buildRoleHierarchy($roles),
        ]);
    }

    /**
     * Get permissions for a role including inherited (API).
     */
    public function getRolePermissions(Role $role)
    {
        $permissions = $role->getAllPermissions();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'is_inherited' => $role->inherit_permissions && $role->parent_role_id,
        ]);
    }
}
