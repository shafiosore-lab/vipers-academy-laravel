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
    /**
     * Features accessible to authenticated users without an organization.
     */
    private const NON_ORG_FEATURES = ['profile', 'password_change', 'notifications'];

    public function __construct(protected RoleHierarchyService $hierarchyService) {}

    /**
     * Gate access by subscription plan and optional feature flag.
     *
     * Usage: Route::middleware('subscription:finance_module')
     */
    public function handle(Request $request, Closure $next, ?string $feature = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        // Super-admins bypass all subscription checks
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        $organization = $this->hierarchyService->getOrganizationForUser($user);

        // No organization — allow only basic features
        if (!$organization) {
            if ($feature && !in_array($feature, self::NON_ORG_FEATURES)) {
                return $this->deny($request, 'You need an active organization to access this feature.');
            }

            return $next($request);
        }

        if (!$organization->hasActiveSubscription()) {
            Log::warning('Subscription inactive', ['user_id' => $user->id, 'org_id' => $organization->id]);

            return $this->deny($request, 'Your organization\'s subscription is inactive. Please renew to continue.');
        }

        $plan = $organization->subscriptionPlan;

        if (!$plan) {
            Log::warning('No subscription plan', ['user_id' => $user->id, 'org_id' => $organization->id]);

            return $this->deny($request, 'No subscription plan found. Please contact support.');
        }

        if ($feature && !$plan->hasFeature($feature) && !$organization->hasFeature($feature)) {
            Log::warning('Feature not in plan', ['user_id' => $user->id, 'org_id' => $organization->id, 'feature' => $feature, 'plan' => $plan->slug]);

            return $this->deny($request, "The '{$feature}' feature is not available on your current plan ({$plan->name}).");
        }

        // Expose subscription context downstream
        $request->merge([
            'subscription_plan' => $plan,
            'organization_id'   => $organization->id,
        ]);

        return $next($request);
    }

    /**
     * Return the appropriate denial response for the request type.
     */
    private function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['error' => 'Access Denied', 'message' => $message], 403);
        }

        if ($request->header('X-Inertia')) {
            return inertia('Errors/Forbidden', ['message' => $message])
                ->toResponse($request)
                ->setStatusCode(403);
        }

        return redirect()->back()->with('error', $message);
    }
}
