<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'password' => 'required|min:8|confirmed',
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
            'registration_date' => now(),
        ];

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'partner',
            'status' => $request->status,
            'partner_details' => $partnerDetails,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully.');
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
