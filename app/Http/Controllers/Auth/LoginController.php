<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on user type
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isPlayer()) {
                // Check if player exists and is approved
                $player = $user->player;
                if ($player && $player->isApproved()) {
                    return redirect()->intended(route('player.portal.dashboard'));
                } else {
                    // Player not approved or doesn't exist
                    Auth::logout();
                    return redirect('/')->with('error', 'Your player account is pending approval. Please contact the academy administration.');
                }
            } elseif ($user->isPartner()) {
                return redirect()->intended(route('partner.dashboard'));
            } else {
                // Default redirect for visitors or other user types
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
