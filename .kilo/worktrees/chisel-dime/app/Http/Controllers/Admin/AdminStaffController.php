<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Partner;
use App\Models\Organization;
use App\Services\NewUserNotificationService;
use App\Services\RoleHierarchyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminStaffController extends Controller
{
    protected $hierarchyService;

    public function __construct()
    {
        $this->hierarchyService = new RoleHierarchyService();
    }

    public function index()
    {
        $currentUser = auth()->user();

        // Different query based on user role
        if ($currentUser->hasRole('super-admin')) {
            // Super admin sees all staff
            $staff = User::where('user_type', 'staff')
                ->with(['roles', 'organization'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } elseif ($currentUser->hasRole('org-admin')) {
            // Org admin sees only their organization's staff
            $organization = $this->hierarchyService->getOrganizationForUser($currentUser);
            if ($organization) {
                $staff = User::where('user_type', 'staff')
                    ->where('organization_id', $organization->id)
                    ->with(['roles', 'organization'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
            } else {
                $staff = collect([]);
            }
        } elseif ($currentUser->isPartner()) {
            // Partner sees staff they created
            $staff = User::where('user_type', 'staff')
                ->where('partner_id', $currentUser->id)
                ->with(['roles', 'partner'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Default - show all staff
            $staff = User::where('user_type', 'staff')
                ->with(['roles', 'organization'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        // Statistics (filtered by organization for non-super-admins)
        $totalStaff = $this->getFilteredStaffCount($currentUser, 'staff');
        $activeStaff = $this->getFilteredStaffCount($currentUser, 'active');
        $coaches = $this->getStaffWithRole($currentUser, 'coach');
        $supportStaff = $this->getStaffWithRole($currentUser, 'support');
        $recentStaff = $this->getFilteredStaffCount($currentUser, 'recent');

        return view('admin.staff.index', compact(
            'staff', 'totalStaff', 'activeStaff', 'coaches', 'supportStaff', 'recentStaff'
        ));
    }

    /**
     * Get filtered staff count based on user role
     */
    protected function getFilteredStaffCount(User $user, string $type): int
    {
        $query = User::where('user_type', 'staff');

        if ($user->hasRole('super-admin')) {
            // See all
        } elseif ($user->hasRole('org-admin')) {
            $org = $this->hierarchyService->getOrganizationForUser($user);
            if ($org) {
                $query->where('organization_id', $org->id);
            }
        } elseif ($user->isPartner()) {
            $query->where('partner_id', $user->id);
        }

        return match($type) {
            'active' => $query->clone()->where('approval_status', 'approved')->count(),
            'recent' => $query->clone()->where('created_at', '>=', now()->startOfMonth())->count(),
            default => $query->count(),
        };
    }

    /**
     * Get staff count with specific role
     */
    protected function getStaffWithRole(User $user, string $roleSlug): int
    {
        $query = User::where('user_type', 'staff')
            ->whereHas('roles', function($q) use ($roleSlug) {
                $q->where('slug', $roleSlug);
            });

        if ($user->hasRole('super-admin')) {
            // See all
        } elseif ($user->hasRole('org-admin')) {
            $org = $this->hierarchyService->getOrganizationForUser($user);
            if ($org) {
                $query->where('organization_id', $org->id);
            }
        } elseif ($user->isPartner()) {
            $query->where('partner_id', $user->id);
        }

        return $query->count();
    }

    public function create()
    {
        // Get the currently logged-in user
        $currentUser = auth()->user();

        // Get available roles based on user's permission level and subscription
        $roles = $this->hierarchyService->getAvailableRolesForUser($currentUser);

        // Get available modules for grouping
        $modules = $this->hierarchyService->getAvailableModulesForUser($currentUser);

        // Get subscription context for display
        $subscriptionContext = $this->hierarchyService->getRoleCreationContext($currentUser);

        // Group roles by module for better UX
        $rolesByModule = $roles->groupBy('module');

        // Log the role availability for debugging
        Log::info('AdminStaffController: Creating staff', [
            'user_id' => $currentUser->id,
            'roles_count' => $roles->count(),
            'subscription_context' => $subscriptionContext,
        ]);

        return view('admin.staff.create', compact('roles', 'rolesByModule', 'modules', 'subscriptionContext'));
    }

    public function store(Request $request)
    {
        $currentUser = auth()->user();

        // Validate request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'role_id' => 'required|exists:roles,id',
            'send_credentials' => 'nullable|boolean',
        ]);

        // Additional checks for uniqueness to prevent race conditions
        if (User::where('phone', $request->phone)->exists()) {
            return back()->withInput()->withErrors(['phone' => 'A user with this phone number already exists.']);
        }

        if (User::where('email', $request->email)->exists()) {
            return back()->withInput()->withErrors(['email' => 'A user with this email address already exists.']);
        }

        // Verify the selected role is available for this user
        $selectedRole = Role::find($request->role_id);

        if (!$this->hierarchyService->canAssignRole($currentUser, $selectedRole)) {
            Log::warning('AdminStaffController: Unauthorized role assignment attempt', [
                'creator_id' => $currentUser->id,
                'role_id' => $request->role_id,
                'role_slug' => $selectedRole->slug,
            ]);
            return back()->withInput()->withErrors(['role_id' => 'You do not have permission to assign this role.']);
        }

        // Check for privilege escalation
        if (!$this->hierarchyService->canUserEscalatePrivilege($currentUser, $selectedRole)) {
            Log::warning('AdminStaffController: Privilege escalation attempt', [
                'creator_id' => $currentUser->id,
                'role_slug' => $selectedRole->slug,
            ]);
            return back()->withInput()->withErrors(['role_id' => 'You cannot assign a role with higher privileges than your own.']);
        }

        // Get organization context
        $organization = $this->hierarchyService->getOrganizationForUser($currentUser);

        $notificationService = new NewUserNotificationService();
        $temporaryPassword = $notificationService->generateTemporaryPassword();

        DB::transaction(function () use ($request, $temporaryPassword, $notificationService, $currentUser, $organization) {
            $userData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($temporaryPassword),
                'user_type' => 'staff',
                'status' => 'active',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $currentUser->id,
            ];

            // Add organization_id if creator is org-admin or super-admin
            if ($organization) {
                $userData['organization_id'] = $organization->id;
            }

            // Add partner_id if creator is a partner
            if ($currentUser->isPartner()) {
                $userData['partner_id'] = $currentUser->id;
            }

            $user = User::create($userData);

            // Use RoleHierarchyService to assign base role + specialized role
            $selectedRole = Role::find($request->role_id);
            $this->hierarchyService->assignBaseRoleWithSpecialization($user, [$selectedRole->slug]);

            // Log role assignment for audit
            Log::info('AdminStaffController: Staff created with role', [
                'created_user_id' => $user->id,
                'assigned_role' => $selectedRole->slug,
                'created_by' => $currentUser->id,
                'organization_id' => $organization?->id,
            ]);

            // Send email notification if requested (default: true)
            $sendCredentials = $request->input('send_credentials', true);
            if ($sendCredentials) {
                $user->refresh(); // Refresh to get the created user
                $notificationService->sendLoginCredentials($user, $temporaryPassword);
            }
        });

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully. Credentials will be sent to their email.');
    }

    public function show(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $currentUser = auth()->user();

        // Check if user has access to view this staff
        if (!$this->canAccessStaff($currentUser, $staff)) {
            abort(403, 'You do not have permission to view this staff member.');
        }

        // Load relationships
        $staff->load(['roles.permissions', 'organization', 'approvedBy']);

        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $currentUser = auth()->user();

        // Check if user has access to edit this staff
        if (!$this->canAccessStaff($currentUser, $staff)) {
            abort(403, 'You do not have permission to edit this staff member.');
        }

        // Get available roles based on permission level
        $roles = $this->hierarchyService->getAvailableRolesForUser($currentUser);

        // Get modules for grouping
        $rolesByModule = $roles->groupBy('module');

        return view('admin.staff.edit', compact('staff', 'roles', 'rolesByModule'));
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $currentUser = auth()->user();

        // Check if user has access to update this staff
        if (!$this->canAccessStaff($currentUser, $staff)) {
            abort(403, 'You do not have permission to update this staff member.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $staff->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        // Verify the selected role is available for this user
        $selectedRole = Role::find($request->role_id);

        if (!$this->hierarchyService->canAssignRole($currentUser, $selectedRole)) {
            return back()->withInput()->withErrors(['role_id' => 'You do not have permission to assign this role.']);
        }

        // Check for privilege escalation
        if (!$this->hierarchyService->canUserEscalatePrivilege($currentUser, $selectedRole)) {
            return back()->withInput()->withErrors(['role_id' => 'You cannot assign a role with higher privileges than your own.']);
        }

        DB::transaction(function () use ($request, $staff, $currentUser) {
            $staff->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Remove old roles and assign new one (keeping base role)
            $staff->roles()->detach();
            $role = Role::find($request->role_id);
            $staff->assignRole($role);

            Log::info('AdminStaffController: Staff updated with new role', [
                'updated_user_id' => $staff->id,
                'new_role' => $role->slug,
                'updated_by' => $currentUser->id,
            ]);
        });

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    /**
     * Check if the current user can access (view/edit) a specific staff member
     */
    protected function canAccessStaff(User $currentUser, User $staff): bool
    {
        // Super admin can access all
        if ($currentUser->hasRole('super-admin')) {
            return true;
        }

        // Org admin can only access their organization's staff
        if ($currentUser->hasRole('org-admin')) {
            $org = $this->hierarchyService->getOrganizationForUser($currentUser);
            if ($org && $staff->organization_id === $org->id) {
                return true;
            }
            return false;
        }

        // Partner can only access staff they created
        if ($currentUser->isPartner()) {
            return $staff->partner_id === $currentUser->id;
        }

        // Default deny
        return false;
    }

    public function activate(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $currentUser = auth()->user();

        if (!$this->canAccessStaff($currentUser, $staff)) {
            abort(403, 'You do not have permission to activate this staff member.');
        }

        $staff->update(['approval_status' => 'approved']);

        Log::info('AdminStaffController: Staff activated', [
            'staff_id' => $staff->id,
            'activated_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member activated successfully.');
    }

    public function deactivate(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $currentUser = auth()->user();

        if (!$this->canAccessStaff($currentUser, $staff)) {
            abort(403, 'You do not have permission to deactivate this staff member.');
        }

        $staff->update(['approval_status' => 'pending']);

        Log::info('AdminStaffController: Staff deactivated', [
            'staff_id' => $staff->id,
            'deactivated_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deactivated successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $currentUser = auth()->user();

        if (!$this->canAccessStaff($currentUser, $staff)) {
            abort(403, 'You do not have permission to delete this staff member.');
        }

        // Prevent self-deletion
        if ($staff->id === $currentUser->id) {
            return redirect()->route('admin.staff.index')->with('error', 'You cannot delete your own account.');
        }

        Log::info('AdminStaffController: Staff deleted', [
            'staff_id' => $staff->id,
            'deleted_by' => $currentUser->id,
        ]);

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    /**
     * API endpoint to get available roles for current user (AJAX)
     */
    public function getAvailableRoles(Request $request)
    {
        $currentUser = auth()->user();
        $roles = $this->hierarchyService->getAvailableRolesForUser($currentUser);

        return response()->json([
            'roles' => $roles->map(function($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,
                    'description' => $role->description,
                    'module' => $role->module,
                    'restriction_status' => $role->restriction_status,
                ];
            }),
            'subscription_context' => $this->hierarchyService->getRoleCreationContext($currentUser),
        ]);
    }
}
