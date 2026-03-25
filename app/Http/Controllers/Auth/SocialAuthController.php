<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RoleHierarchyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Create new user
                $nameParts = explode(' ', $googleUser->getName(), 2);

                // Build user data with trial fields
                $userData = [
                    'first_name' => $nameParts[0] ?? '',
                    'last_name' => $nameParts[1] ?? '',
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'user_type' => 'player',
                    'approval_status' => 'approved',
                    'password' => bcrypt(Str::random(16)),
                ];

                // Add trial fields if columns exist
                if (Schema::hasColumn('users', 'is_on_trial')) {
                    $userData['is_on_trial'] = true;
                }
                if (Schema::hasColumn('users', 'trial_ends_at')) {
                    $userData['trial_ends_at'] = now()->addDays(10);
                }
                if (Schema::hasColumn('users', 'trial_type')) {
                    $userData['trial_type'] = 'player';
                }
                if (Schema::hasColumn('users', 'trial_auto_activated')) {
                    $userData['trial_auto_activated'] = true;
                }

                $user = User::create($userData);

                // Assign player role
                $playerRole = \App\Models\Role::where('slug', 'player')->first();
                if ($playerRole) {
                    $user->assignRole($playerRole);
                }

                Auth::login($user);
            }

            // Use centralized RoleHierarchyService for deterministic dashboard routing
            $hierarchyService = new RoleHierarchyService();
            $user->load('roles');
            $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);
            return redirect()->route($dashboardRoute);
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
        }
    }

    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook OAuth callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Create new user
                $nameParts = explode(' ', $facebookUser->getName(), 2);

                // Build user data with trial fields
                $userData = [
                    'first_name' => $nameParts[0] ?? '',
                    'last_name' => $nameParts[1] ?? '',
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'user_type' => 'player',
                    'approval_status' => 'approved',
                    'password' => bcrypt(Str::random(16)),
                ];

                // Add trial fields if columns exist
                if (Schema::hasColumn('users', 'is_on_trial')) {
                    $userData['is_on_trial'] = true;
                }
                if (Schema::hasColumn('users', 'trial_ends_at')) {
                    $userData['trial_ends_at'] = now()->addDays(10);
                }
                if (Schema::hasColumn('users', 'trial_type')) {
                    $userData['trial_type'] = 'player';
                }
                if (Schema::hasColumn('users', 'trial_auto_activated')) {
                    $userData['trial_auto_activated'] = true;
                }

                $user = User::create($userData);

                // Assign player role
                $playerRole = \App\Models\Role::where('slug', 'player')->first();
                if ($playerRole) {
                    $user->assignRole($playerRole);
                }

                Auth::login($user);
            }

            // Use centralized RoleHierarchyService for deterministic dashboard routing
            $hierarchyService = new RoleHierarchyService();
            $user->load('roles');
            $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);
            return redirect()->route($dashboardRoute);
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Facebook login failed. Please try again.');
        }
    }
}
