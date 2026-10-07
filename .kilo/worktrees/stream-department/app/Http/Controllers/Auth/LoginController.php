<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RoleHierarchyService;
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

            // Log login success for debugging
            \Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'roles' => $user->roles->pluck('slug')->toArray(),
            ]);

            // Use RoleHierarchyService to determine correct dashboard
            $hierarchyService = new RoleHierarchyService();
            $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);

            \Log::info('Redirecting user to dashboard', [
                'user_id' => $user->id,
                'dashboard_route' => $dashboardRoute,
            ]);

            return redirect()->route($dashboardRoute);
        }

        // Log failed login attempt
        \Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
