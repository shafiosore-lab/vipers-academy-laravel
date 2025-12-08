<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
                $user = User::create([
                    'first_name' => $nameParts[0] ?? '',
                    'last_name' => $nameParts[1] ?? '',
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'user_type' => 'player',
                    'approval_status' => 'approved', // Auto-approve social logins
                    'password' => bcrypt(Str::random(16)), // Random password
                ]);

                // Assign player role
                $playerRole = \App\Models\Role::where('slug', 'player')->first();
                if ($playerRole) {
                    $user->assignRole($playerRole);
                }

                Auth::login($user);
            }

            return redirect()->intended('/dashboard');
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
                $user = User::create([
                    'first_name' => $nameParts[0] ?? '',
                    'last_name' => $nameParts[1] ?? '',
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'user_type' => 'player',
                    'approval_status' => 'approved', // Auto-approve social logins
                    'password' => bcrypt(Str::random(16)), // Random password
                ]);

                // Assign player role
                $playerRole = \App\Models\Role::where('slug', 'player')->first();
                if ($playerRole) {
                    $user->assignRole($playerRole);
                }

                Auth::login($user);
            }

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Facebook login failed. Please try again.');
        }
    }
}
