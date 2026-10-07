<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

/**
 * RoleDisplayService - Handles role display logic with clean separation
 * between UI layer and permission validation.
 *
 * This service determines:
 * - Primary role (highest priority based on hierarchy)
 * - Secondary roles (additional roles that require explicit access)
 * - Whether elevated role indicators should be shown
 * - Dashboard routing based on role hierarchy
 * - Permission checks before rendering role-specific UI elements
 *
 * Configuration is loaded from config/roles.php and config/dashboards.php
 */
class RoleDisplayService
{
    /**
     * Get role priority from config
     */
    protected function getRolePriorityConfig(): array
    {
        return config('roles.priority', []);
    }

    /**
     * Get role levels from config
     */
    protected function getRoleLevels(): array
    {
        return config('roles.levels', []);
    }

    /**
     * Get dashboard configuration from config/dashboards.php
     */
    protected function getDashboardsConfig(): array
    {
        return config('dashboards', []);
    }

    /**
     * Get all dashboard keys for hierarchy
     */
    protected function getDashboardHierarchy(): array
    {
        return config('dashboards.hierarchy', []);
    }

    /**
     * Get dashboard config by key
     */
    protected function getDashboardConfig(string $dashboardKey): ?array
    {
        $dashboards = $this->getDashboardsConfig();
        return $dashboards[$dashboardKey] ?? null;
    }

    /**
     * Get role-to-dashboard mapping from dashboards config
     */
    protected function getRoleDashboardMapping(): array
    {
        $mapping = [];
        $dashboards = $this->getDashboardsConfig();
        $hierarchy = $this->getDashboardHierarchy();

        foreach ($hierarchy as $dashboardKey) {
            $config = $dashboards[$dashboardKey] ?? null;
            if ($config && isset($config['allowed_roles'])) {
                foreach ($config['allowed_roles'] as $role) {
                    $mapping[$role] = $dashboardKey;
                }
            }
        }

        return $mapping;
    }

    /**
     * Check if a role is an admin role (can override dashboards)
     */
    protected function isAdminRole(string $roleSlug): bool
    {
        return in_array($roleSlug, config('roles.admin_roles', []));
    }

    /**
     * Get the primary role for a user based on hierarchy priority
     */
    public function getPrimaryRole(User $user): ?Role
    {
        $userRoles = $user->roles;

        if ($userRoles->isEmpty()) {
            return null;
        }

        $priorityConfig = $this->getRolePriorityConfig();

        return $userRoles->sortByDesc(function ($role) use ($priorityConfig) {
            return $priorityConfig[$role->slug] ?? 0;
        })->first();
    }

    /**
     * Get the primary role slug for a user
     */
    public function getPrimaryRoleSlug(User $user): ?string
    {
        $primaryRole = $this->getPrimaryRole($user);
        return $primaryRole ? $primaryRole->slug : null;
    }

    /**
     * Get all secondary roles (roles other than primary)
     */
    public function getSecondaryRoles(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $primaryRole = $this->getPrimaryRole($user);

        if (!$primaryRole) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        return $user->roles->filter(function ($role) use ($primaryRole) {
            return $role->id !== $primaryRole->id;
        });
    }

    /**
     * Check if user has explicitly granted access to view elevated role indicators
     */
    public function hasElevatedRoleAccess(User $user): bool
    {
        // Super admin always has elevated access
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Check if user has explicitly been granted elevated role display access
        return $user->getAttribute('show_elevated_roles') === true
            || $user->hasPermission(config('roles.elevated_display_permission', 'view_elevated_roles'));
    }

    /**
     * Determine if elevated role indicators should be displayed
     */
    public function shouldShowElevatedIndicators(User $user): bool
    {
        return $this->hasElevatedRoleAccess($user);
    }

    /**
     * Get the display name for user's primary role
     */
    public function getPrimaryRoleDisplayName(User $user): string
    {
        $primaryRole = $this->getPrimaryRole($user);

        if (!$primaryRole) {
            return 'User';
        }

        return ucwords(str_replace(['-', '_'], ' ', $primaryRole->name));
    }

    /**
     * Get all roles that should be displayed in the taskbar
     * Only returns primary role items unless elevated access is granted
     */
    public function getVisibleRolesForTaskbar(User $user): array
    {
        $primaryRole = $this->getPrimaryRole($user);

        if (!$primaryRole) {
            return [];
        }

        // If user has elevated access, show all roles
        if ($this->hasElevatedRoleAccess($user)) {
            return $user->roles->pluck('slug')->toArray();
        }

        // Otherwise, only show primary role
        return [$primaryRole->slug];
    }

    /**
     * Check if a specific role should be visible in the taskbar
     */
    public function isRoleVisibleInTaskbar(User $user, string $roleSlug): bool
    {
        $visibleRoles = $this->getVisibleRolesForTaskbar($user);
        return in_array($roleSlug, $visibleRoles);
    }

    /**
     * Get the role priority level for sorting
     */
    public function getRolePriority(string $roleSlug): int
    {
        $priorityConfig = $this->getRolePriorityConfig();
        return $priorityConfig[$roleSlug] ?? 0;
    }

    /**
     * Check if user can switch to a different dashboard/role
     */
    public function canSwitchDashboard(User $user): bool
    {
        // Must have elevated access
        if (!$this->hasElevatedRoleAccess($user)) {
            return false;
        }

        // Must have multiple roles
        return $user->roles->count() > 1;
    }

    /**
     * Get available dashboards for switching (only if user has access)
     */
    public function getSwitchableDashboards(User $user): array
    {
        if (!$this->canSwitchDashboard($user)) {
            return [];
        }

        $dashboards = [];
        $userTypeService = new UserTypeService();
        $roleDashboardMapping = $this->getRoleDashboardMapping();

        foreach ($user->roles as $role) {
            if (!$this->isRoleVisibleInTaskbar($user, $role->slug)) {
                continue;
            }

            $dashboardKey = $roleDashboardMapping[$role->slug] ?? null;
            if (!$dashboardKey) {
                continue;
            }

            $dashboards[$dashboardKey] = [
                'name' => $userTypeService->getUserTypeDisplayName($dashboardKey),
                'route' => $this->getDashboardRoute($dashboardKey),
                'icon' => $this->getDashboardIcon($dashboardKey),
                'is_primary' => $role->id === $this->getPrimaryRole($user)?->id,
            ];
        }

        return $dashboards;
    }

    /**
     * Get dashboard route by dashboard key
     */
    protected function getDashboardRoute(string $dashboardKey): string
    {
        $config = $this->getDashboardConfig($dashboardKey);
        return $config['route'] ?? 'dashboard';
    }

    /**
     * Get dashboard icon by dashboard key
     */
    protected function getDashboardIcon(string $dashboardKey): string
    {
        $config = $this->getDashboardConfig($dashboardKey);
        return $config['icon'] ?? 'fas fa-tachometer-alt';
    }

    /**
     * Check if user has permission to access specific taskbar item
     */
    public function canAccessTaskbarItem(User $user, string $permission): bool
    {
        // Super admin has access to everything
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission($permission);
    }

    /**
     * Filter taskbar items based on user permissions and role visibility
     */
    public function filterTaskbarItemsByPermission(User $user, array $items): array
    {
        // Super admin sees everything
        if ($user->isSuperAdmin()) {
            return $items;
        }

        return array_filter($items, function ($item) use ($user) {
            if (!isset($item['permission'])) {
                return true;
            }

            return $this->canAccessTaskbarItem($user, $item['permission']);
        });
    }

    /**
     * Get complete role display configuration for the UI
     */
    public function getRoleDisplayConfig(User $user): array
    {
        return [
            'primary_role' => $this->getPrimaryRoleSlug($user),
            'primary_role_display' => $this->getPrimaryRoleDisplayName($user),
            'show_elevated' => $this->shouldShowElevatedIndicators($user),
            'can_switch' => $this->canSwitchDashboard($user),
            'switchable_dashboards' => $this->getSwitchableDashboards($user),
            'visible_roles' => $this->getVisibleRolesForTaskbar($user),
            'is_elevated_user' => $this->isElevatedUser($user),
        ];
    }

    /**
     * Check if user has any elevated role
     */
    public function isElevatedUser(User $user): bool
    {
        foreach ($user->roles as $role) {
            if ($this->isPrimaryDashboardRole($role->slug)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if a role is a primary dashboard role
     */
    protected function isPrimaryDashboardRole(string $roleSlug): bool
    {
        $roleDashboardMapping = $this->getRoleDashboardMapping();
        return isset($roleDashboardMapping[$roleSlug]);
    }

    /**
     * Get cached role config for performance
     */
    public function getCachedRoleConfig(User $user, string $key, $callback)
    {
        $cacheEnabled = config('roles.cache.enabled', true);

        if (!$cacheEnabled) {
            return $callback();
        }

        $ttl = config('roles.cache.ttl', 3600);
        $cacheKey = "role_config:{$user->id}:{$key}";

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Get primary dashboard role configuration for a user
     * This is the main method for determining dashboard access
     */
    public function getPrimaryDashboardRole(User $user): ?array
    {
        // Check for admin override first
        $overrideColumn = config('roles.override_column', 'dashboard_role_override');
        $override = $user->getAttribute($overrideColumn);

        if ($override) {
            $dashboardKey = $this->getDashboardKeyForRole($override);
            if ($dashboardKey) {
                $config = $this->getDashboardConfig($dashboardKey);
                return [
                    'dashboard' => $dashboardKey,
                    'config' => $config,
                    'role' => $override,
                    'is_override' => true,
                ];
            }
        }

        // Get user's roles and match against dashboard hierarchy
        $userRoles = $user->roles->pluck('slug')->toArray();
        $roleDashboardMapping = $this->getRoleDashboardMapping();
        $hierarchy = $this->getDashboardHierarchy();
        $priorityConfig = $this->getRolePriorityConfig();

        // Sort roles by priority
        usort($userRoles, function ($a, $b) use ($priorityConfig) {
            return ($priorityConfig[$b] ?? 0) - ($priorityConfig[$a] ?? 0);
        });

        // Find first matching dashboard
        foreach ($userRoles as $roleSlug) {
            if (isset($roleDashboardMapping[$roleSlug])) {
                $dashboardKey = $roleDashboardMapping[$roleSlug];
                $config = $this->getDashboardConfig($dashboardKey);

                return [
                    'dashboard' => $dashboardKey,
                    'config' => $config,
                    'role' => $roleSlug,
                    'is_override' => false,
                ];
            }
        }

        return null;
    }

    /**
     * Get dashboard key for a role
     */
    protected function getDashboardKeyForRole(string $roleSlug): ?string
    {
        $roleDashboardMapping = $this->getRoleDashboardMapping();
        return $roleDashboardMapping[$roleSlug] ?? null;
    }

    /**
     * Get the route name for user's primary dashboard
     */
    public function getPrimaryDashboardRoute(User $user): ?string
    {
        $primaryDashboard = $this->getPrimaryDashboardRole($user);

        if ($primaryDashboard && isset($primaryDashboard['config']['route'])) {
            return $primaryDashboard['config']['route'];
        }

        return null;
    }

    /**
     * Get the layout for user's primary dashboard
     */
    public function getPrimaryDashboardLayout(User $user): string
    {
        $primaryDashboard = $this->getPrimaryDashboardRole($user);

        if ($primaryDashboard && isset($primaryDashboard['config']['layout'])) {
            return $primaryDashboard['config']['layout'];
        }

        return 'layouts.dashboard';
    }

    /**
     * Get taskbar configuration for user's primary dashboard
     */
    public function getPrimaryDashboardTaskbar(User $user): ?string
    {
        $primaryDashboard = $this->getPrimaryDashboardRole($user);

        if ($primaryDashboard && isset($primaryDashboard['config']['taskbar'])) {
            return $primaryDashboard['config']['taskbar'];
        }

        return null;
    }

    /**
     * Check if user can override dashboard (admin only)
     */
    public function canOverrideDashboard(User $user): bool
    {
        foreach ($user->roles as $role) {
            if ($this->isAdminRole($role->slug)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get role level (platform, organization, team, member)
     */
    public function getRoleLevel(string $roleSlug): ?string
    {
        $levels = $this->getRoleLevels();

        foreach ($levels as $level => $roles) {
            if (in_array($roleSlug, $roles)) {
                return $level;
            }
        }

        return null;
    }
}
