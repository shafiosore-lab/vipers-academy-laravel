<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Services\NewUserNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminPartnerController extends Controller
{
    public function index()
    {
        $partners = User::where('user_type', 'partner')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.partners.index', compact('partners'));
    }

    public function show(User $partner)
    {
        if ($partner->user_type !== 'partner') {
            abort(404);
        }

        return view('admin.partners.show', compact('partner'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:football_club,school,academy,other',
            'contact_person' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'partnership_type' => 'required|in:platform_access,scouting_services,training_programs,custom_solutions',
            'expected_users' => 'required|integer|min:1|max:10000',
            'additional_requirements' => 'nullable|string',
            'role_id' => 'nullable|exists:roles,id',
            'send_credentials' => 'nullable|boolean',
        ]);

        $notificationService = new NewUserNotificationService();
        $temporaryPassword = $notificationService->generateTemporaryPassword();

        $partnerDetails = [
            'organization_name' => $request->organization_name,
            'organization_type' => $request->organization_type,
            'contact_person' => $request->contact_person,
            'contact_position' => $request->contact_position,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'partnership_type' => $request->partnership_type,
            'expected_users' => $request->expected_users,
            'additional_requirements' => $request->additional_requirements,
            'registration_date' => now(),
        ];

        DB::transaction(function () use ($request, $temporaryPassword, $notificationService) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($temporaryPassword),
                'user_type' => 'partner',
                'status' => 'active', // Auto-approve when created by super admin
                'approval_status' => 'approved', // Auto-approve
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'partner_details' => $partnerDetails,
            ]);

            // Assign partner role if specified (optional customization)
            if ($request->role_id) {
                $role = Role::find($request->role_id);
                $user->assignRole($role);
            }
            // Note: Partners can access partner dashboard based on user_type = 'partner'
            // Roles are optional and can be used for granular permission control

            // Send email notification if requested (default: true)
            $sendCredentials = $request->input('send_credentials', true);
            if ($sendCredentials) {
                $user->refresh();
                $notificationService->sendLoginCredentials($user, $temporaryPassword);
            }
        });

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully. Credentials will be sent to their email.');
    }

    public function edit(User $partner)
    {
        if ($partner->user_type !== 'partner') {
            abort(404);
        }

        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, User $partner)
    {
        if ($partner->user_type !== 'partner') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $partner->id,
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:football_club,school,academy,other',
            'contact_person' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'partnership_type' => 'required|in:platform_access,scouting_services,training_programs,custom_solutions',
            'expected_users' => 'required|integer|min:1|max:10000',
            'additional_requirements' => 'nullable|string',
            'status' => 'required|in:active,pending,rejected',
        ]);

        $partnerDetails = [
            'organization_name' => $request->organization_name,
            'organization_type' => $request->organization_type,
            'contact_person' => $request->contact_person,
            'contact_position' => $request->contact_position,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'partnership_type' => $request->partnership_type,
            'expected_users' => $request->expected_users,
            'additional_requirements' => $request->additional_requirements,
            'registration_date' => $partner->partner_details['registration_date'] ?? now(),
        ];

        $partner->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => 'partner',
            'status' => $request->status,
            'partner_details' => $partnerDetails,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(User $partner)
    {
        if ($partner->user_type !== 'partner') {
            abort(404);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully.');
    }

    public function approve(User $partner)
    {
        if ($partner->user_type !== 'partner') {
            abort(404);
        }

        $partner->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Partner approved successfully.');
    }

    public function reject(User $partner)
    {
        if ($partner->user_type !== 'partner') {
            abort(404);
        }

        $partner->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Partner rejected successfully.');
    }
}
