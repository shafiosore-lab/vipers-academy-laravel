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

        // DIAGNOSTIC LOGGING
        \Log::info('RoleMiddleware: User details', [
            'user_id' => $user->id,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'approval_status' => $user->approval_status,
            'status' => $user->status,
            'is_approved' => $user->isApproved(),
            'roles' => $user->roles->pluck('slug', 'name')->toArray(),
        ]);

        // Check if user is approved
        if (!$user->isApproved()) {
            \Log::warning('RoleMiddleware: User not approved', [
                'user_id' => $user->id,
                'approval_status' => $user->approval_status
            ]);
            return redirect('/')->with('error', 'Your account is pending approval. Please contact the academy administration.');
        }

        // Check role requirement - support multiple roles separated by |
        $roles = explode('|', $role);
        \Log::info('RoleMiddleware: Checking roles', [
            'required_roles' => $roles,
            'user_has_any_role' => count(array_filter($roles, fn($r) => $user->hasRole(trim($r)))) > 0,
            'checked_roles' => array_map(fn($r) => trim($r), $roles),
        ]);

        if (!count(array_filter($roles, fn($r) => $user->hasRole(trim($r))))) {
            \Log::warning('RoleMiddleware: Access denied - missing required role', [
                'user_id' => $user->id,
                'required_roles' => $roles,
                'user_roles' => $user->roles->pluck('slug')->toArray(),
            ]);
            abort(403, 'Access denied. Insufficient permissions.');
        }

        // Check permission requirement if specified
        if ($permission && !$user->hasPermission($permission)) {
            \Log::warning('RoleMiddleware: Access denied - missing permission', [
                'user_id' => $user->id,
                'required_permission' => $permission,
                'user_permissions' => $user->getAllPermissions()->pluck('slug')->toArray(),
            ]);
            abort(403, 'Access denied. Insufficient permissions.');
        }

        \Log::info('RoleMiddleware: Access granted', ['user_id' => $user->id]);
        return $next($request);
    }
}
