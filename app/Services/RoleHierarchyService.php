<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class RoleHierarchyService
{
    /**
     * Base roles that are automatically assigned to all staff members
     */
    private const BASE_ROLE = 'staff-base';

    /**
     * Role hierarchy mapping (higher index = higher priority)
     */
    private const ROLE_HIERARCHY = [
        'staff-base' => 0,
        'player' => 1,
        'parent' => 2,
        'assistant-coach' => 3,
        'coach' => 4,
        'team-manager' => 5,
        'media-officer' => 6,
        'safeguarding-officer' => 7,
        'finance-officer' => 8,
        'head-coach' => 9,
        'admin-operations' => 10,
        'marketing-admin' => 11,
        'scouting-admin' => 12,
        'coaching-admin' => 13,
        'finance-admin' => 14,
        'operations-admin' => 15,
        'org-admin' => 50,
        'super-admin' => 100,
    ];

    /**
     * Dashboard route mapping for each role
     */
    private const DASHBOARD_ROUTES = [
        'super-admin' => 'admin.dashboard',
        'org-admin' => 'organization.dashboard',
        'marketing-admin' => 'admin.dashboard',
        'scouting-admin' => 'admin.dashboard',
        'coaching-admin' => 'admin.dashboard',
        'finance-admin' => 'admin.dashboard',
        'operations-admin' => 'admin.dashboard',
        'admin-operations' => 'admin.dashboard',
        'head-coach' => 'coach.dashboard',
        'assistant-coach' => 'coach.dashboard',
        'coach' => 'coach.dashboard',
        'team-manager' => 'manager.dashboard',
        'media-officer' => 'media.dashboard',
        'safeguarding-officer' => 'welfare.dashboard',
        'finance-officer' => 'finance.dashboard',
        'partner-marketing' => 'partner.dashboard',
        'partner-scouting' => 'partner.dashboard',
        'partner-operations' => 'partner.dashboard',
        'player' => 'player.portal.dashboard',
        'parent' => 'parent.dashboard',
        'staff-base' => 'coach.dashboard', // Default to coach dashboard
    ];

    /**
     * Get the base role for all staff members
     */
    public function getBaseRole(): ?Role
    {
        return Role::where('slug', self::BASE_ROLE)->first();
    }

    /**
     * Ensure base role exists and create it if it doesn't
     */
    public function ensureBaseRoleExists(): Role
    {
        return Role::updateOrCreate(
            ['slug' => self::BASE_ROLE],
            [
                'name' => 'Staff Base',
                'description' => 'Base role for all staff members - inherits permissions to specialized roles',
                'type' => 'partner_staff',
                'is_default' => false,
                'level' => 0,
            ]
        );
    }

    /**
     * Assign base role to a user along with their specialized roles
     */
    public function assignBaseRoleWithSpecialization(User $user, array $roleSlugs): void
    {
        // Ensure base role exists
        $baseRole = $this->ensureBaseRoleExists();

        // Assign base role if not already assigned
        if (!$user->hasRole(self::BASE_ROLE)) {
            $user->assignRole($baseRole);
        }

        // Assign specialized roles
        foreach ($roleSlugs as $roleSlug) {
            $role = Role::where('slug', $roleSlug)->first();
            if ($role && !$user->hasRole($roleSlug)) {
                $user->assignRole($role);
            }
        }
    }

    /**
     * Get the dashboard route for a user based on their highest role
     */
    public function getDashboardRouteForUser(User $user): string
    {
        $userRoles = $user->roles->pluck('slug')->toArray();

        // Find the highest priority role
        $highestRole = $this->getHighestPriorityRole($userRoles);

        return self::DASHBOARD_ROUTES[$highestRole] ?? 'home';
    }

    /**
     * Get the highest priority role from a list of roles
     */
    public function getHighestPriorityRole(array $roleSlugs): string
    {
        $highestRole = null;
        $highestPriority = -1;

        foreach ($roleSlugs as $role) {
            $priority = self::ROLE_HIERARCHY[$role] ?? 0;
            if ($priority > $highestPriority) {
                $highestPriority = $priority;
                $highestRole = $role;
            }
        }

        return $highestRole ?? 'staff-base';
    }

    /**
     * Check if a role has higher priority than another
     */
    public function hasHigherPriority(string $role1, string $role2): bool
    {
        $priority1 = self::ROLE_HIERARCHY[$role1] ?? 0;
        $priority2 = self::ROLE_HIERARCHY[$role2] ?? 0;
        return $priority1 > $priority2;
    }

    /**
     * Get all roles that inherit from a base role
     */
    public function getInheritedRoles(string $baseRole): array
    {
        $basePriority = self::ROLE_HIERARCHY[$baseRole] ?? 0;

        return array_filter(
            self::ROLE_HIERARCHY,
            fn($priority) => $priority >= $basePriority && $priority < self::ROLE_HIERARCHY['super-admin']
        );
    }

    /**
     * Get role info including dashboard route and permissions
     */
    public function getRoleInfo(string $roleSlug): array
    {
        $role = Role::where('slug', $roleSlug)->first();

        return [
            'role' => $role,
            'dashboard_route' => self::DASHBOARD_ROUTES[$roleSlug] ?? 'home',
            'priority' => self::ROLE_HIERARCHY[$roleSlug] ?? 0,
            'is_admin' => in_array($roleSlug, ['super-admin', 'marketing-admin', 'scouting-admin', 'coaching-admin', 'finance-admin', 'operations-admin', 'admin-operations']),
            'is_staff' => in_array($roleSlug, ['head-coach', 'assistant-coach', 'coach', 'team-manager', 'media-officer', 'safeguarding-officer', 'finance-officer']),
            'is_partner' => str_starts_with($roleSlug, 'partner-'),
        ];
    }

    /**
     * Get all available dashboard routes
     */
    public function getAllDashboardRoutes(): array
    {
        return [
            'admin.dashboard' => 'Admin Dashboard',
            'coach.dashboard' => 'Coach Dashboard',
            'manager.dashboard' => 'Team Manager Dashboard',
            'media.dashboard' => 'Media Officer Dashboard',
            'welfare.dashboard' => 'Welfare Officer Dashboard',
            'finance.dashboard' => 'Finance Officer Dashboard',
            'partner.dashboard' => 'Partner Dashboard',
            'player.portal.dashboard' => 'Player Portal',
            'parent.dashboard' => 'Parent Portal',
        ];
    }

    /**
     * Get roles that should access a specific dashboard
     */
    public function getRolesForDashboard(string $dashboardRoute): array
    {
        return array_filter(
            self::DASHBOARD_ROUTES,
            fn($route) => $route === $dashboardRoute
        );
    }
}
