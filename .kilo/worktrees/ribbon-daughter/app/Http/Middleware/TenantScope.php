<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantScope
{
    /**
     * Bind the authenticated user's organization into the service container
     * so downstream code can resolve the current tenant without touching the request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->hasRole('super-admin') && $user->organization_id) {
            app()->instance('tenant_id', $user->organization_id);
            app()->instance('organization_id', $user->organization_id);
        }

        return $next($request);
    }
}
