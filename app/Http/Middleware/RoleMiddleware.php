<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Gate access by required role(s) and an optional permission.
     * Approval status is handled upstream by CheckUserStatus.
     *
     * Usage: Route::middleware('role:coach|head-coach,manage_training')
     */
    public function handle(Request $request, Closure $next, string $role, ?string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $roles = array_map('trim', explode('|', $role));

        if (!$user->hasAnyRole($roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        if ($permission && !$user->hasPermission($permission)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        return $next($request);
    }
}
