<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Role-based access control for admin/staff areas.
     * Approval and account status checks are handled upstream by CheckUserStatus.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        // Super-admins bypass all role checks — full access granted.
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $permitted = [
            'marketing-admin', 'scouting-admin', 'operations-admin',
            'admin-operations', 'partner-operations', 'coaching-admin',
            'finance-admin', 'org-admin',
            // Staff roles with basic admin access
            'coach', 'assistant-coach', 'head-coach',
        ];

        if (!$user->hasAnyRole($permitted)) {
            // Redirect to their own dashboard rather than showing a hard 403
            // Use RoleHierarchyService to determine correct dashboard
            $hierarchyService = new \App\Services\RoleHierarchyService();
            $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);

            return redirect()->route($dashboardRoute)
                ->with('error', 'You do not have permission to access that area.');
        }

        return $next($request);
    }
}
