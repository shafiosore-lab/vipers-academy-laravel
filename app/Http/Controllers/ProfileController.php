<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Use role-appropriate profile view based on user roles
        if ($user->hasAnyRole(['super-admin', 'admin-operations', 'operations-admin'])) {
            return view('admin.profile.index', [
                'user' => $user,
            ]);
        } elseif ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach', 'team-manager', 'finance-officer', 'media-officer', 'safeguarding-officer'])) {
            // Staff users - use staff layout with their role-based dashboard
            return view('staff.profile.edit', [
                'user' => $user,
            ]);
        } elseif ($user->hasAnyRole(['player'])) {
            return view('player.portal.profile', [
                'user' => $user,
            ]);
        } elseif ($user->hasAnyRole(['parent', 'partner'])) {
            return view('partner.profile.edit', [
                'user' => $user,
            ]);
        }

        // Default: use admin profile view
        return view('admin.profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
