<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PartnerMiddleware
{
    /**
     * Roles permitted to access partner-area routes.
     * Approval status is handled upstream by CheckUserStatus.
     */
    private const PERMITTED_ROLES = [
        'partner', 'parent', 'partner-marketing', 'partner-scouting', 'partner-operations',
        'coach', 'assistant-coach', 'head-coach',
    ];

    /**
     * User types permitted to access partner-area routes.
     */
    private const PERMITTED_TYPES = ['partner', 'staff'];

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        // Super-admins bypass all checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (in_array($user->user_type, self::PERMITTED_TYPES) || $user->hasAnyRole(self::PERMITTED_ROLES)) {
            return $next($request);
        }

        // Redirect to their own dashboard rather than showing a hard 403
        // Use RoleHierarchyService to determine correct dashboard
        $hierarchyService = new \App\Services\RoleHierarchyService();
        $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);

        return redirect()->route($dashboardRoute)
            ->with('error', 'You do not have permission to access that area.');
    }
}
