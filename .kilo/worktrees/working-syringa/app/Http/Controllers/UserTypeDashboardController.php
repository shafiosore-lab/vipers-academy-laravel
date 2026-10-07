<?php

namespace App\Http\Controllers;

use App\Services\UserTypeService;
use App\Services\DashboardRouteService;
use App\Services\RoleDisplayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTypeDashboardController extends Controller
{
    protected UserTypeService $userTypeService;
    protected DashboardRouteService $dashboardRouteService;
    protected RoleDisplayService $roleDisplayService;

    public function __construct()
    {
        $this->userTypeService = new UserTypeService();
        $this->dashboardRouteService = new DashboardRouteService();
        $this->roleDisplayService = new RoleDisplayService();
    }

    /**
     * Redirect user to appropriate dashboard based on user type
     */
    public function redirectToDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $dashboardRoute = $this->dashboardRouteService->getUserDashboardRoute();
        return redirect($dashboardRoute);
    }

    /**
     * Show dashboard for current user type
     */
    public function showDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get role display configuration
        $roleDisplayConfig = $this->roleDisplayService->getRoleDisplayConfig($user);

        // Get primary user type
        $primaryRoleSlug = $this->roleDisplayService->getPrimaryRoleSlug($user);
        $userType = $primaryRoleSlug ? $this->mapRoleToUserType($primaryRoleSlug) : 'user';

        // Get dashboard configuration
        $dashboardConfig = $this->dashboardRouteService->getDashboardConfig($user);

        // Get taskbar configuration (filtered by primary role only by default)
        $taskbars = $this->dashboardRouteService->getTaskbarConfig($userType, $user);

        // Get user-specific dashboard data
        $dashboardData = $this->getDashboardData($user, $userType);

        return view('dashboard.' . $userType . '.index', [
            'userType' => $dashboardConfig,
            'taskbars' => $taskbars,
            'dashboardData' => $dashboardData
        ]);
    }

    /**
     * Show specific dashboard for user type
     */
    public function showSpecificDashboard(string $userType)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user can access this dashboard type
        $canAccess = $this->checkDashboardAccess($user, $userType);

        if (!$canAccess) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to this dashboard.');
        }

        $dashboardConfig = $this->dashboardRouteService->getDashboardConfig($user);
        $taskbars = $this->dashboardRouteService->getTaskbarConfig($userType, $user);

        // Get user-specific dashboard data
        $dashboardData = $this->getDashboardData($user, $userType);

        return view('dashboard.' . $userType . '.index', [
            'userType' => $dashboardConfig,
            'taskbars' => $taskbars,
            'dashboardData' => $dashboardData
        ]);
    }

    /**
     * Check if user can access specific dashboard
     */
    protected function checkDashboardAccess(User $user, string $dashboardType): bool
    {
        // Use RoleDisplayService to check access
        $visibleRoles = $this->roleDisplayService->getVisibleRolesForTaskbar($user);

        $requiredRole = $this->mapUserTypeToRole($dashboardType);

        if ($requiredRole) {
            return in_array($requiredRole, $visibleRoles);
        }

        // Fallback to old logic
        return $this->userTypeService->canAccessDashboard($user, $dashboardType);
    }

    /**
     * Map user type to role slug
     */
    protected function mapUserTypeToRole(string $userType): ?string
    {
        return match ($userType) {
            'super-admin' => 'super-admin',
            'org-admin' => 'org-admin',
            'staff' => 'coach',
            'partner' => 'partner',
            'parent' => 'parent',
            'player' => 'player',
            'trial' => 'trial',
            default => null,
        };
    }

    /**
     * Map role slug to user type
     */
    protected function mapRoleToUserType(string $roleSlug): string
    {
        return match ($roleSlug) {
            'super-admin' => 'super-admin',
            'org-admin' => 'org-admin',
            'head-coach', 'coach', 'assistant-coach', 'team-manager',
            'finance-officer', 'finance-admin', 'operations-admin',
            'media-officer', 'safeguarding-officer' => 'staff',
            'partner' => 'partner',
            'parent' => 'parent',
            'player' => 'player',
            'trial' => 'trial',
            default => 'user',
        };
    }

    /**
     * Get dashboard data based on user type
     */
    protected function getDashboardData(User $user, string $userType): array
    {
        $data = [
            'user' => $user,
            'user_type' => $userType,
            'notifications' => [],
            'recent_activity' => [],
            'quick_stats' => []
        ];

        switch ($userType) {
            case 'super-admin':
                $data = array_merge($data, $this->getSuperAdminDashboardData($user));
                break;
            case 'org-admin':
                $data = array_merge($data, $this->getOrgAdminDashboardData($user));
                break;
            case 'staff':
                $data = array_merge($data, $this->getStaffDashboardData($user));
                break;
            case 'player':
                $data = array_merge($data, $this->getPlayerDashboardData($user));
                break;
            case 'parent':
                $data = array_merge($data, $this->getParentDashboardData($user));
                break;
            case 'trial':
                $data = array_merge($data, $this->getTrialDashboardData($user));
                break;
        }

        return $data;
    }

    /**
     * Get Super Admin dashboard data
     */
    protected function getSuperAdminDashboardData(User $user): array
    {
        return [
            'quick_stats' => [
                [
                    'label' => 'Active Organizations',
                    'value' => \App\Models\Organization::where('status', 'active')->count(),
                    'icon' => 'fas fa-building',
                    'bgClass' => 'bg-blue-500'
                ],
                [
                    'label' => 'Total Users',
                    'value' => \App\Models\User::count(),
                    'icon' => 'fas fa-users',
                    'bgClass' => 'bg-green-500'
                ],
                [
                    'label' => 'Active Subscriptions',
                    'value' => \App\Models\Subscription::where('status', 'active')->count(),
                    'icon' => 'fas fa-credit-card',
                    'bgClass' => 'bg-purple-500'
                ],
                [
                    'label' => 'System Health',
                    'value' => 'Good',
                    'icon' => 'fas fa-heartbeat',
                    'bgClass' => 'bg-teal-500'
                ]
            ],
            'recent_activity' => $this->getRecentActivity(),
            'notifications' => $this->getNotifications()
        ];
    }

    /**
     * Get Organization Admin dashboard data
     */
    protected function getOrgAdminDashboardData(User $user): array
    {
        return [
            'quick_stats' => [
                [
                    'label' => 'Active Players',
                    'value' => \App\Models\Player::where('organization_id', $user->organization_id)->count(),
                    'icon' => 'fas fa-users',
                    'bgClass' => 'bg-blue-500'
                ],
                [
                    'label' => 'Active Staff',
                    'value' => \App\Models\Staff::where('organization_id', $user->organization_id)->count(),
                    'icon' => 'fas fa-user-tie',
                    'bgClass' => 'bg-green-500'
                ],
                [
                    'label' => 'Active Programs',
                    'value' => \App\Models\Program::where('organization_id', $user->organization_id)->count(),
                    'icon' => 'fas fa-calendar-alt',
                    'bgClass' => 'bg-purple-500'
                ],
                [
                    'label' => 'Pending Approvals',
                    'value' => \App\Models\Approval::where('organization_id', $user->organization_id)->where('status', 'pending')->count(),
                    'icon' => 'fas fa-clock',
                    'bgClass' => 'bg-yellow-500'
                ]
            ],
            'recent_activity' => $this->getRecentActivity(),
            'notifications' => $this->getNotifications()
        ];
    }

    /**
     * Get Staff dashboard data
     */
    protected function getStaffDashboardData(User $user): array
    {
        return [
            'quick_stats' => [
                [
                    'label' => 'My Tasks',
                    'value' => 5,
                    'icon' => 'fas fa-tasks',
                    'bgClass' => 'bg-blue-500'
                ],
                [
                    'label' => 'Pending Reviews',
                    'value' => 3,
                    'icon' => 'fas fa-eye',
                    'bgClass' => 'bg-green-500'
                ],
                [
                    'label' => 'Upcoming Events',
                    'value' => 2,
                    'icon' => 'fas fa-calendar',
                    'bgClass' => 'bg-purple-500'
                ],
                [
                    'label' => 'Messages',
                    'value' => 8,
                    'icon' => 'fas fa-envelope',
                    'bgClass' => 'bg-teal-500'
                ]
            ],
            'recent_activity' => $this->getRecentActivity(),
            'notifications' => $this->getNotifications()
        ];
    }

    /**
     * Get Player dashboard data
     */
    protected function getPlayerDashboardData(User $user): array
    {
        return [
            'quick_stats' => [
                [
                    'label' => 'Training Sessions',
                    'value' => 12,
                    'icon' => 'fas fa-dumbbell',
                    'bgClass' => 'bg-blue-500'
                ],
                [
                    'label' => 'Matches Played',
                    'value' => 8,
                    'icon' => 'fas fa-futbol',
                    'bgClass' => 'bg-green-500'
                ],
                [
                    'label' => 'Progress Reports',
                    'value' => 3,
                    'icon' => 'fas fa-chart-line',
                    'bgClass' => 'bg-purple-500'
                ],
                [
                    'label' => 'Messages',
                    'value' => 5,
                    'icon' => 'fas fa-comments',
                    'bgClass' => 'bg-teal-500'
                ]
            ],
            'recent_activity' => $this->getRecentActivity(),
            'notifications' => $this->getNotifications()
        ];
    }

    /**
     * Get Parent dashboard data
     */
    protected function getParentDashboardData(User $user): array
    {
        return [
            'quick_stats' => [
                [
                    'label' => 'My Child Progress',
                    'value' => 'Good',
                    'icon' => 'fas fa-star',
                    'bgClass' => 'bg-blue-500'
                ],
                [
                    'label' => 'Upcoming Events',
                    'value' => 4,
                    'icon' => 'fas fa-calendar-alt',
                    'bgClass' => 'bg-green-500'
                ],
                [
                    'label' => 'Messages',
                    'value' => 2,
                    'icon' => 'fas fa-envelope',
                    'bgClass' => 'bg-purple-500'
                ],
                [
                    'label' => 'Payments Due',
                    'value' => 'KES 2,500',
                    'icon' => 'fas fa-money-bill',
                    'bgClass' => 'bg-yellow-500'
                ]
            ],
            'recent_activity' => $this->getRecentActivity(),
            'notifications' => $this->getNotifications()
        ];
    }

    /**
     * Get Trial dashboard data
     */
    protected function getTrialDashboardData(User $user): array
    {
        $trialDaysLeft = $user->trial_ends_at ? $user->trial_ends_at->diffInDays(now()) : 0;

        return [
            'quick_stats' => [
                [
                    'label' => 'Trial Days Left',
                    'value' => $trialDaysLeft,
                    'icon' => 'fas fa-clock',
                    'bgClass' => 'bg-blue-500'
                ],
                [
                    'label' => 'Features Available',
                    'value' => 'Basic',
                    'icon' => 'fas fa-star',
                    'bgClass' => 'bg-green-500'
                ],
                [
                    'label' => 'Demo Videos',
                    'value' => 5,
                    'icon' => 'fas fa-play-circle',
                    'bgClass' => 'bg-purple-500'
                ],
                [
                    'label' => 'Upgrade Options',
                    'value' => 'Available',
                    'icon' => 'fas fa-arrow-up',
                    'bgClass' => 'bg-orange-500'
                ]
            ],
            'recent_activity' => $this->getRecentActivity(),
            'notifications' => $this->getNotifications(),
            'trial_info' => [
                'days_left' => $trialDaysLeft,
                'features_available' => ['Basic Player Management', 'Limited Reports', 'Demo Access'],
                'upgrade_cta' => 'Upgrade to Full Access'
            ]
        ];
    }

    /**
     * Get recent activity
     */
    protected function getRecentActivity(): array
    {
        return [
            ['title' => 'New player registered', 'time' => '2 hours ago', 'icon' => 'fas fa-user-plus', 'color' => 'blue'],
            ['title' => 'Payment received', 'time' => '4 hours ago', 'icon' => 'fas fa-money-bill', 'color' => 'green'],
            ['title' => 'Document uploaded', 'time' => '1 day ago', 'icon' => 'fas fa-file-alt', 'color' => 'purple'],
        ];
    }

    /**
     * Get notifications
     */
    protected function getNotifications(): array
    {
        return [
            ['title' => 'New message from coach', 'time' => '10 minutes ago', 'read' => false],
            ['title' => 'Training session reminder', 'time' => '1 hour ago', 'read' => false],
            ['title' => 'Payment due notice', 'time' => '2 days ago', 'read' => true],
        ];
    }

    /**
     * Show dashboard switcher for multi-role users
     * Only accessible if user has elevated role access
     */
    public function showDashboardSwitcher()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user can switch dashboards
        if (!$this->roleDisplayService->canSwitchDashboard($user)) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to switch dashboards.');
        }

        $availableDashboards = $this->roleDisplayService->getSwitchableDashboards($user);
        $currentDashboard = $this->dashboardRouteService->getDashboardConfig($user);

        return view('dashboard.switcher', [
            'availableDashboards' => $availableDashboards,
            'currentDashboard' => $currentDashboard
        ]);
    }
}
