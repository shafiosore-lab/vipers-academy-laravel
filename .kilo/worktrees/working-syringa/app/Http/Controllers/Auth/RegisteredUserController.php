<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Redirect to the main registration choice page for role-based registration.
     */
    public function create()
    {
        // Show the unified signup page directly
        return view('auth.signup');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Support both first_name/last_name and name for flexibility
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['nullable', 'in:player,organization,coach,team_manager,partner,general'],
        ]);

        // Use first_name and last_name if provided, otherwise fall back to name
        $firstName = $request->first_name ?? ($request->name ? explode(' ', $request->name)[0] : '');
        $lastName = $request->last_name ?? ($request->name ? (count(explode(' ', $request->name)) > 1 ? implode(' ', array_slice(explode(' ', $request->name), 1)) : '') : '');

        // Determine user type based on account_type
        $accountType = $request->input('account_type', 'general');
        $userTypeMapping = [
            'player' => 'player',
            'coach' => 'staff',
            'team_manager' => 'staff',
            'organization' => 'staff',
            'partner' => 'partner',
            'general' => 'general',
        ];

        $userData = [
            'name' => $firstName . ' ' . $lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $userTypeMapping[$accountType] ?? 'general',
            'status' => 'active',
        ];

        // Add trial fields if columns exist
        if (Schema::hasColumn('users', 'is_on_trial')) {
            $userData['is_on_trial'] = true;
        }
        if (Schema::hasColumn('users', 'trial_ends_at')) {
            $userData['trial_ends_at'] = now()->addDays(10);
        }
        if (Schema::hasColumn('users', 'trial_type')) {
            $userData['trial_type'] = 'general';
        }
        if (Schema::hasColumn('users', 'trial_auto_activated')) {
            $userData['trial_auto_activated'] = true;
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        // Use centralized RoleHierarchyService for deterministic dashboard routing
        $hierarchyService = new \App\Services\RoleHierarchyService();
        $user->load('roles');
        $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);
        return redirect()->route($dashboardRoute);
    }
}
