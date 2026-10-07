<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

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
        $hierarchyService = new \App\Services\RoleHierarchyService();
        $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);

        \Log::info('Redirecting user to dashboard', [
            'user_id' => $user->id,
            'dashboard_route' => $dashboardRoute,
        ]);

        return redirect()->route($dashboardRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
