<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @param  string|null  $permission
     */
    public function handle(Request $request, Closure $next, string $role, string $permission = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return redirect('/')->with('error', 'Your account is pending approval. Please contact the academy administration.');
        }

        // Check role requirement
        if (!$user->hasRole($role)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        // Check permission requirement if specified
        if ($permission && !$user->hasPermission($permission)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
