<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    /**
     * Gate access by subscription feature.
     *
     * Usage: Route::middleware('feature:finance_module')
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = Auth::user();

        // Super-admins bypass all feature checks
        if ($user?->hasRole('super-admin')) {
            return $next($request);
        }

        $organization = $user?->organization;

        if (!$organization) {
            return redirect()->route('dashboard')
                ->with('error', 'No organization found for your account.');
        }

        if (!$organization->hasActiveSubscription()) {
            return redirect()->route('subscription.expired')
                ->with('error', 'Your subscription has expired. Please renew to continue.');
        }

        if (!$organization->hasFeature($feature)) {
            return redirect()->back()
                ->with('error', 'This feature is not included in your current plan. Please upgrade to access it.');
        }

        return $next($request);
    }
}
