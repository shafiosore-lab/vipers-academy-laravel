<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            \Log::warning('AdminMiddleware: User not authenticated', ['ip' => $request->ip(), 'url' => $request->url()]);
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        // DIAGNOSTIC LOGGING
        \Log::info('AdminMiddleware: User details', [
            'user_id' => $user->id,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'approval_status' => $user->approval_status,
            'status' => $user->status,
            'is_admin' => $user->isAdmin(),
            'is_active' => $user->isActive(),
            'is_approved' => $user->isApproved(),
            'roles' => $user->roles->pluck('slug', 'name')->toArray(),
        ]);

        // Check if user has any admin role or is staff with appropriate permissions
        // Use SLUGS to match the role middleware expectations
        $adminRoles = ['super-admin', 'marketing-admin', 'scouting-admin', 'operations-admin', 'admin-operations', 'partner-operations', 'coaching-admin', 'finance-admin'];
        $staffRoles = ['coach', 'assistant-coach', 'head-coach']; // Staff roles that should have basic admin access

        $userRoles = $user->roles->pluck('slug')->toArray();
        $hasAdminRole = $user->hasAnyRole($adminRoles);
        $hasStaffRole = $user->hasAnyRole($staffRoles);
        $hasRequiredRole = $hasAdminRole || $hasStaffRole;

        \Log::info('AdminMiddleware: Role check', [
            'user_id' => $user->id,
            'user_roles' => $userRoles,
            'required_admin_roles' => $adminRoles,
            'required_staff_roles' => $staffRoles,
            'has_admin_role' => $hasAdminRole,
            'has_staff_role' => $hasStaffRole,
            'has_required_role' => $hasRequiredRole,
        ]);

        if (!$hasRequiredRole) {
            \Log::warning('AdminMiddleware: Access denied - missing roles', ['user_id' => $user->id, 'roles' => $userRoles]);
            abort(403, 'Access denied. Insufficient permissions.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            \Log::warning('AdminMiddleware: User not approved, logging out', ['user_id' => $user->id, 'approval_status' => $user->approval_status]);
            Auth::logout();
            return redirect('/')->with('error', 'Your account is pending approval. Please contact the academy administration.');
        }

        \Log::info('AdminMiddleware: Access granted', ['user_id' => $user->id]);
        return $next($request);
    }
}
