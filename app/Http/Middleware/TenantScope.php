<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantScope
{
    /**
     * Handle an incoming request.
     * This middleware automatically scopes all queries to the current user's organization.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated or is a super admin, skip tenant scoping
        if (!$user || $user->hasRole('super-admin')) {
            return $next($request);
        }

        // Set the current tenant ID for global access
        if ($user->organization_id) {
            app()->instance('tenant_id', $user->organization_id);
            app()->instance('organization_id', $user->organization_id);

            // Log for debugging
            \Log::debug('TenantScope: Set tenant_id', [
                'user_id' => $user->id,
                'organization_id' => $user->organization_id,
            ]);
        }

        return $next($request);
    }
}
