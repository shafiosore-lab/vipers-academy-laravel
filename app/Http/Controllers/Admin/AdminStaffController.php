<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Partner;
use App\Services\NewUserNotificationService;
use App\Services\RoleHierarchyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staff = User::where('user_type', 'staff')
            ->with(['roles', 'partner'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistics
        $totalStaff = User::where('user_type', 'staff')->count();
        $activeStaff = User::where('user_type', 'staff')->where('approval_status', 'approved')->count();
        $coaches = User::where('user_type', 'staff')
            ->whereHas('roles', function($q) {
                $q->where('slug', 'coach');
            })->count();
        $supportStaff = User::where('user_type', 'staff')
            ->whereHas('roles', function($q) {
                $q->whereNotIn('slug', ['coach', 'assistant_coach']);
            })->count();
        $recentStaff = User::where('user_type', 'staff')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        return view('admin.staff.index', compact(
            'staff', 'totalStaff', 'activeStaff', 'coaches', 'supportStaff', 'recentStaff'
        ));
    }

    public function create()
    {
        $roles = Role::where('type', 'partner_staff')->get();

        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
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

        $notificationService = new NewUserNotificationService();
        $temporaryPassword = $notificationService->generateTemporaryPassword();

        DB::transaction(function () use ($request, $temporaryPassword, $notificationService) {
            $user = User::create([
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
                'approved_by' => auth()->id(),
            ]);

            // Use RoleHierarchyService to assign base role + specialized role
            $hierarchyService = new RoleHierarchyService();
            $selectedRole = Role::find($request->role_id);
            $hierarchyService->assignBaseRoleWithSpecialization($user, [$selectedRole->slug]);

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

        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $roles = Role::where('type', 'partner_staff')->get();

        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $staff->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        DB::transaction(function () use ($request, $staff) {
            $staff->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Remove old role and assign new one
            $staff->roles()->detach();
            $role = Role::find($request->role_id);
            $staff->assignRole($role);
        });

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function activate(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $staff->update(['approval_status' => 'approved']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member activated successfully.');
    }

    public function deactivate(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $staff->update(['approval_status' => 'pending']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deactivated successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->user_type !== 'staff') {
            abort(404);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
