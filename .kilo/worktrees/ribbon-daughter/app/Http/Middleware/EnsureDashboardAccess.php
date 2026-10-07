<?php

namespace App\Http\Middleware;

use App\Services\RoleDisplayService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureDashboardAccess
{
    /**
     * Dashboard keys that are platform-level and bypass organization scoping.
     */
    private const PLATFORM_DASHBOARDS = ['superadmin'];

    public function __construct(protected RoleDisplayService $roleDisplayService) {}

    /**
     * Verify the user holds a role permitted to access the requested dashboard.
     *
     * Usage: Route::middleware('dashboard.access:superadmin')
     */
    public function handle(Request $request, Closure $next, string $dashboardKey): Response
    {
        $user = Auth::user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $dashboardConfig = config("dashboards.{$dashboardKey}");

        if (!$dashboardConfig) {
            Log::warning("Dashboard config not found: {$dashboardKey}");

            return redirect()->route('dashboard')
                ->with('error', 'Dashboard not found.');
        }

        $allowedRoles = $dashboardConfig['allowed_roles'] ?? [];

        if (!$this->hasRoleAccess($user, $allowedRoles)) {
            Log::warning('Unauthorized dashboard access attempt', [
                'user_id'    => $user->id,
                'user_roles' => $user->roles->pluck('slug'),
                'dashboard'  => $dashboardKey,
                'ip'         => $request->ip(),
            ]);

            $primaryRoute = $this->roleDisplayService->getPrimaryDashboardRoute($user);

            return redirect()->to($primaryRoute ? route($primaryRoute) : route('dashboard'))
                ->with('error', 'You do not have permission to access this dashboard.');
        }

        if (!$this->hasOrganizationAccess($user, $request, $dashboardKey)) {
            return redirect()->route('dashboard')
                ->with('error', 'You can only access your own organization\'s dashboard.');
        }

        return $next($request);
    }

    /**
     * Check whether the user's roles or override attribute match the allowed roles.
     */
    private function hasRoleAccess($user, array $allowedRoles): bool
    {
        if ($user->roles->pluck('slug')->intersect($allowedRoles)->isNotEmpty()) {
            return true;
        }

        $override = $user->getAttribute(config('roles.override_column', 'dashboard_role_override'));

        return $override && in_array($override, $allowedRoles);
    }

    /**
     * Ensure the user belongs to the organization they are scoped to.
     */
    private function hasOrganizationAccess($user, Request $request, string $dashboardKey): bool
    {
        if (in_array($dashboardKey, self::PLATFORM_DASHBOARDS) || $user->hasRole('super-admin')) {
            return true;
        }

        if (!$user->organization_id) {
            return false;
        }

        $requestedOrgId = $request->get('organization_id');

        return !$requestedOrgId || $user->organization_id == $requestedOrgId;
    }
}
