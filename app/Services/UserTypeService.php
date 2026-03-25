<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserTypeService
{
    /**
     * Determine the primary user type for dashboard routing
     */
    public function getPrimaryUserType(User $user): string
    {
        // Super Admin has highest priority
        if ($user->isSuperAdmin()) {
            return 'super-admin';
        }

        // Check for organization admin role
        if ($user->hasRole('org-admin')) {
            return 'org-admin';
        }

        // Check for staff roles
        $staffRoles = ['coach', 'assistant-coach', 'head-coach', 'team-manager', 'media-officer', 'safeguarding-officer', 'finance-officer', 'finance-admin', 'operations-admin'];
        foreach ($staffRoles as $role) {
            if ($user->hasRole($role)) {
                return 'staff';
            }
        }

        // Check for partner role
        if ($user->hasRole('partner')) {
            return 'partner';
        }

        // Check for parent role
        if ($user->hasRole('parent')) {
            return 'parent';
        }

        // Check for player role
        if ($user->hasRole('player')) {
            return 'player';
        }

        // Check for trial users
        if ($this->isTrialUser($user)) {
            return 'trial';
        }

        // Default fallback
        return 'user';
    }

    /**
     * Get dashboard route by user type directly
     */
    public function getDashboardRouteByType(string $userType): string
    {
        $routes = [
            'super-admin' => 'super-admin.dashboard',
            'org-admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
            'partner' => 'partner.dashboard',
            'parent' => 'parent.dashboard',
            'player' => 'player.portal.dashboard',
            'trial' => 'trial.dashboard',
            'user' => 'dashboard',
        ];

        return $routes[$userType] ?? 'dashboard';
    }

    /**
     * Check if user is on trial
     */
    public function isTrialUser(User $user): bool
    {
        return $user->is_on_trial && $user->trial_ends_at && $user->trial_ends_at->isFuture();
    }

    /**
     * Check if user's trial has expired
     */
    public function isTrialExpired(User $user): bool
    {
        if (!$user->is_on_trial || !$user->trial_ends_at) {
            return false;
        }

        return $user->trial_ends_at->isPast();
    }

    /**
     * Get dashboard route for user
     */
    public function getDashboardRoute(User $user): string
    {
        $userType = $this->getPrimaryUserType($user);

        $routes = [
            'super-admin' => 'super-admin.dashboard',
            'org-admin' => 'admin.dashboard',
            'staff' => $this->getStaffDashboardRoute($user),
            'partner' => 'partner.dashboard',
            'parent' => 'parent.dashboard',
            'player' => 'player.portal.dashboard',
            'trial' => 'trial.dashboard',
            'user' => 'dashboard'
        ];

        return $routes[$userType] ?? 'dashboard';
    }

    /**
     * Determine staff dashboard route based on specific role
     */
    protected function getStaffDashboardRoute(User $user): string
    {
        $coachRoles = ['coach', 'assistant-coach', 'head-coach'];
        $managerRoles = ['team-manager'];
        $mediaRoles = ['media-officer'];
        $welfareRoles = ['safeguarding-officer'];
        $financeRoles = ['finance-officer', 'finance-admin', 'operations-admin'];

        if (array_intersect($user->roles->pluck('slug')->toArray(), $coachRoles)) {
            return 'coach.dashboard';
        } elseif (array_intersect($user->roles->pluck('slug')->toArray(), $managerRoles)) {
            return 'manager.dashboard';
        } elseif (array_intersect($user->roles->pluck('slug')->toArray(), $mediaRoles)) {
            return 'media.dashboard';
        } elseif (array_intersect($user->roles->pluck('slug')->toArray(), $welfareRoles)) {
            return 'welfare.dashboard';
        } elseif (array_intersect($user->roles->pluck('slug')->toArray(), $financeRoles)) {
            return 'finance.dashboard';
        }

        // Default staff dashboard
        return 'staff.dashboard';
    }

    /**
     * Get available dashboard routes for user (for multi-role users)
     */
    public function getAvailableDashboards(User $user): array
    {
        $dashboards = [];

        if ($user->isSuperAdmin()) {
            $dashboards['super-admin'] = [
                'name' => 'Super Admin Dashboard',
                'route' => 'super-admin.dashboard',
                'icon' => 'fas fa-crown'
            ];
        }

        if ($user->hasRole('org-admin')) {
            $dashboards['org-admin'] = [
                'name' => 'Organization Admin Dashboard',
                'route' => 'admin.dashboard',
                'icon' => 'fas fa-building'
            ];
        }

        if ($this->hasStaffRoles($user)) {
            $dashboards['staff'] = [
                'name' => 'Staff Dashboard',
                'route' => $this->getStaffDashboardRoute($user),
                'icon' => 'fas fa-user-tie'
            ];
        }

        if ($user->hasRole('partner')) {
            $dashboards['partner'] = [
                'name' => 'Partner Dashboard',
                'route' => 'partner.dashboard',
                'icon' => 'fas fa-handshake'
            ];
        }

        if ($user->hasRole('parent')) {
            $dashboards['parent'] = [
                'name' => 'Parent Dashboard',
                'route' => 'parent.dashboard',
                'icon' => 'fas fa-users'
            ];
        }

        if ($user->hasRole('player')) {
            $dashboards['player'] = [
                'name' => 'Player Portal',
                'route' => 'player.portal.dashboard',
                'icon' => 'fas fa-user-graduate'
            ];
        }

        if ($this->isTrialUser($user)) {
            $dashboards['trial'] = [
                'name' => 'Trial Dashboard',
                'route' => 'trial.dashboard',
                'icon' => 'fas fa-clock'
            ];
        }

        return $dashboards;
    }

    /**
     * Check if user has any staff roles
     */
    protected function hasStaffRoles(User $user): bool
    {
        $staffRoles = ['coach', 'assistant-coach', 'head-coach', 'team-manager', 'media-officer', 'safeguarding-officer', 'finance-officer', 'finance-admin', 'operations-admin'];
        return $user->roles->pluck('slug')->intersect($staffRoles)->isNotEmpty();
    }

    /**
     * Get user type display name
     */
    public function getUserTypeDisplayName(string $userType): string
    {
        $names = [
            'super-admin' => 'Super Administrator',
            'org-admin' => 'Organization Administrator',
            'staff' => 'Staff Member',
            'partner' => 'Partner',
            'parent' => 'Parent/Guardian',
            'player' => 'Player',
            'trial' => 'Trial User',
            'user' => 'User'
        ];

        return $names[$userType] ?? ucfirst($userType);
    }

    /**
     * Check if user has access to specific dashboard
     */
    public function canAccessDashboard(User $user, string $dashboardType): bool
    {
        $userType = $this->getPrimaryUserType($user);

        // Super admin can access all dashboards
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Check specific permissions
        switch ($dashboardType) {
            case 'super-admin':
                return $user->isSuperAdmin();
            case 'org-admin':
                return $user->hasRole('org-admin') || $user->isSuperAdmin();
            case 'staff':
                return $this->hasStaffRoles($user);
            case 'partner':
                return $user->hasRole('partner');
            case 'parent':
                return $user->hasRole('parent');
            case 'player':
                return $user->hasRole('player');
            case 'trial':
                return $this->isTrialUser($user);
            default:
                return false;
        }
    }
}
