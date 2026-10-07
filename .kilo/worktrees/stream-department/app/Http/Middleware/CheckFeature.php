<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    /**
     * Handle an incoming request.
     * Checks if the organization's subscription includes the requested feature.
     *
     * Usage:
     * Route::middleware(['feature:finance_module'])->group(function() {...});
     * Route::middleware(['feature:api_access'])->group(function() {...});
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = Auth::user();

        // Super admins bypass feature checks
        if ($user && $user->hasRole('super-admin')) {
            return $next($request);
        }

        // Non-authenticated users or users without organization can't access features
        if (!$user || !$user->organization_id) {
            abort(403, 'Access denied. Organization not found.');
        }

        // Get the organization and check feature
        $organization = $user->organization;

        if (!$organization) {
            abort(403, 'Access denied. Organization not found.');
        }

        // Check if organization has active subscription
        if (!$organization->hasActiveSubscription()) {
            return redirect()->route('subscription.expired')
                ->with('error', 'Your subscription is not active. Please renew to access features.');
        }

        // Check if feature is available in the plan
        if (!$organization->hasFeature($feature)) {
            abort(403, "The '{$feature}' feature is not available in your current subscription plan. Please upgrade to access this feature.");
        }

        return $next($request);
    }
}
