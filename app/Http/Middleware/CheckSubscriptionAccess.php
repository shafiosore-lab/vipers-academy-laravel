<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\SubscriptionPlan;
use App\Services\RoleHierarchyService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionAccess
{
    protected $hierarchyService;

    public function __construct()
    {
        $this->hierarchyService = new RoleHierarchyService();
    }

    /**
     * Handle an incoming request.
     *
     * This middleware ensures that:
     * 1. Users can only access features allowed by their subscription plan
     * 2. Organization admins cannot access features beyond their plan limits
     * 3. Role assignments respect subscription boundaries
     */
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        // Super admin bypasses all subscription restrictions
        if ($user->hasRole('super-admin')) {
            Log::debug('CheckSubscriptionAccess: Super admin bypass', [
                'user_id' => $user->id,
                'feature' => $feature,
            ]);
            return $next($request);
        }

        // Get the organization for this user
        $organization = $this->hierarchyService->getOrganizationForUser($user);

        if (!$organization) {
            // Users without organization get minimal access
            Log::debug('CheckSubscriptionAccess: No organization found', [
                'user_id' => $user->id,
            ]);

            if ($feature) {
                // Check if feature is available for non-org users
                if (!$this->isFeatureAvailableForNonOrg($feature)) {
                    return $this->denyAccess($request, 'You need an active organization to access this feature.');
                }
            }

            return $next($request);
        }

        // Check if organization has active subscription
        if (!$organization->hasActiveSubscription()) {
            Log::warning('CheckSubscriptionAccess: No active subscription', [
                'user_id' => $user->id,
                'organization_id' => $organization->id,
            ]);

            return $this->denyAccess($request, 'Your organization does not have an active subscription. Please renew to continue.');
        }

        // Get the subscription plan
        $plan = $organization->subscriptionPlan;

        if (!$plan) {
            Log::warning('CheckSubscriptionAccess: No subscription plan', [
                'user_id' => $user->id,
                'organization_id' => $organization->id,
            ]);

            return $this->denyAccess($request, 'No subscription plan found. Please contact support.');
        }

        // Check specific feature access if provided
        if ($feature) {
            if (!$this->checkFeatureAccess($feature, $plan, $organization)) {
                Log::warning('CheckSubscriptionAccess: Feature not available', [
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                    'feature' => $feature,
                    'plan' => $plan->slug,
                ]);

                return $this->denyAccess($request, "The '{$feature}' feature is not available on your current plan ({$plan->name}).");
            }
        }

        // Log successful access check
        Log::debug('CheckSubscriptionAccess: Access granted', [
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'plan' => $plan->slug,
            'feature' => $feature,
        ]);

        // Store subscription context in request for later use
        $request->merge([
            'subscription_plan' => $plan,
            'organization_id' => $organization->id,
        ]);

        return $next($request);
    }

    /**
     * Check if a specific feature is available for the plan
     */
    protected function checkFeatureAccess(string $feature, SubscriptionPlan $plan, Organization $organization): bool
    {
        // Check plan features
        if ($plan->hasFeature($feature)) {
            return true;
        }

        // Check organization-level feature overrides
        if ($organization->hasFeature($feature)) {
            return true;
        }

        // Feature not found in plan
        return false;
    }

    /**
     * Check if feature is available for users without organization
     */
    protected function isFeatureAvailableForNonOrg(string $feature): bool
    {
        // Basic features available to all authenticated users
        $basicFeatures = [
            'profile',
            'password_change',
            'notifications',
        ];

        return in_array($feature, $basicFeatures);
    }

    /**
     * Handle denied access with appropriate response
     */
    protected function denyAccess(Request $request, string $message): Response
    {
        // Check if this is an AJAX/API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'error' => 'Access Denied',
                'message' => $message,
            ], 403);
        }

        // Check if this is an Inertia request
        if ($request->header('X-Inertia')) {
            return inertia('Errors/Forbidden', [
                'message' => $message,
            ])->toResponse($request)->setStatusCode(403);
        }

        // Regular request - redirect back with error
        return redirect()->back()
            ->with('error', $message);
    }
}
