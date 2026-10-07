<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardRouteService - Handles dashboard routing and taskbar configuration
 *
 * Uses RoleDisplayService for permission validation to ensure clean separation
 * between UI layer and authorization logic.
 */
class DashboardRouteService
{
    protected RoleDisplayService $roleDisplayService;
    protected UserTypeService $userTypeService;

    public function __construct()
    {
        $this->roleDisplayService = new RoleDisplayService();
        $this->userTypeService = new UserTypeService();
    }

    /**
     * Get the appropriate dashboard route for the authenticated user
     * Uses primary dashboard role to determine routing
     */
    public function getUserDashboardRoute(): string
    {
        $user = Auth::user();

        if (!$user) {
            return route('login');
        }

        // Use the new primary dashboard method (supports overrides)
        $primaryRoute = $this->roleDisplayService->getPrimaryDashboardRoute($user);

        if ($primaryRoute && Route::has($primaryRoute)) {
            return route($primaryRoute);
        }

        // Fallback to main dashboard
        return route('dashboard');
    }

    /**
     * Get dashboard configuration for user type
     */
    public function getDashboardConfig(User $user): array
    {
        $roleDisplayConfig = $this->roleDisplayService->getRoleDisplayConfig($user);
        $userType = $this->roleDisplayService->getPrimaryRoleSlug($user);

        return [
            'user_type' => $userType,
            'display_name' => $roleDisplayConfig['primary_role_display'],
            'dashboard_route' => $this->getUserDashboardRoute(),
            'available_dashboards' => $roleDisplayConfig['switchable_dashboards'],
            'is_trial' => $this->userTypeService->isTrialUser($user),
            'trial_expired' => $this->userTypeService->isTrialExpired($user),
            'can_access_all' => $user->isSuperAdmin(),
            // New role display configuration
            'show_elevated' => $roleDisplayConfig['show_elevated'],
            'can_switch' => $roleDisplayConfig['can_switch'],
            'is_primary_only' => !$roleDisplayConfig['show_elevated'],
        ];
    }

    /**
     * Get taskbar configuration for user type with role-based filtering
     * Now uses RoleDisplayService for proper permission separation
     */
    public function getTaskbarConfig(string $userType, ?User $user = null): array
    {
        // If no user provided, get from auth
        if (!$user) {
            $user = Auth::user();
        }

        // If still no user, return trial taskbars
        if (!$user) {
            return $this->getTaskbarByType('trial', null);
        }

        // Get the actual user type from user object using RoleDisplayService
        $actualUserType = $this->roleDisplayService->getPrimaryRoleSlug($user);

        // Override userType if provided and different
        $userType = $actualUserType ? $this->mapRoleToUserType($actualUserType) : $userType;

        return $this->getTaskbarByType($userType, $user);
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
     * Get taskbar configuration by user type
     * Uses RoleDisplayService for permission filtering
     */
    protected function getTaskbarByType(string $userType, ?User $user): array
    {
        $taskbars = [
            'super-admin' => $this->getSuperAdminTaskbar($user),
            'org-admin' => $this->getOrgAdminTaskbar($user),
            'staff' => $this->getStaffTaskbar($user),
            'player' => $this->getPlayerTaskbar($user),
            'parent' => $this->getParentTaskbar($user),
            'trial' => $this->getTrialTaskbar($user),
        ];

        return $taskbars[$userType] ?? $this->getTrialTaskbar($user);
    }

    /**
     * Get Super Admin taskbar configuration
     */
    protected function getSuperAdminTaskbar(?User $user): array
    {
        return [
            'primary' => [
                'name' => 'Platform Control',
                'icon' => 'fas fa-cog',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Organization Management',
                        'route' => 'super-admin.organizations.index',
                        'icon' => 'fas fa-building',
                        'permission' => 'manage_organizations'
                    ],
                    [
                        'name' => 'User Management',
                        'route' => 'super-admin.users.index',
                        'icon' => 'fas fa-users',
                        'permission' => 'manage_users'
                    ],
                    [
                        'name' => 'System Settings',
                        'route' => 'super-admin.settings',
                        'icon' => 'fas fa-sliders-h',
                        'permission' => 'manage_system'
                    ]
                ])
            ],
            'secondary' => [
                'name' => 'Analytics & Compliance',
                'icon' => 'fas fa-chart-bar',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Platform Analytics',
                        'route' => 'super-admin.analytics',
                        'icon' => 'fas fa-chart-line',
                        'permission' => 'view_analytics'
                    ],
                    [
                        'name' => 'Audit Logs',
                        'route' => 'super-admin.audit-logs',
                        'icon' => 'fas fa-file-alt',
                        'permission' => 'view_audit_logs'
                    ],
                    [
                        'name' => 'Compliance Reports',
                        'route' => 'super-admin.compliance',
                        'icon' => 'fas fa-shield-alt',
                        'permission' => 'view_compliance'
                    ]
                ])
            ]
        ];
    }

    /**
     * Get Org Admin taskbar configuration
     */
    protected function getOrgAdminTaskbar(?User $user): array
    {
        return [
            'primary' => [
                'name' => 'Academy Operations',
                'icon' => 'fas fa-tachometer-alt',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Player Management',
                        'route' => 'admin.players.index',
                        'icon' => 'fas fa-users',
                        'permission' => 'manage_players'
                    ],
                    [
                        'name' => 'Staff Management',
                        'route' => 'admin.staff.index',
                        'icon' => 'fas fa-user-tie',
                        'permission' => 'manage_staff'
                    ],
                    [
                        'name' => 'Program Management',
                        'route' => 'admin.programs.index',
                        'icon' => 'fas fa-calendar-alt',
                        'permission' => 'manage_programs'
                    ]
                ])
            ],
            'secondary' => [
                'name' => 'Finance & Compliance',
                'icon' => 'fas fa-money-bill-wave',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Financial Reports',
                        'route' => 'admin.finance.reports',
                        'icon' => 'fas fa-chart-pie',
                        'permission' => 'view_financial_reports'
                    ],
                    [
                        'name' => 'Document Management',
                        'route' => 'admin.documents.index',
                        'icon' => 'fas fa-folder',
                        'permission' => 'manage_documents'
                    ],
                    [
                        'name' => 'Compliance Tracking',
                        'route' => 'admin.compliance',
                        'icon' => 'fas fa-check-circle',
                        'permission' => 'view_compliance'
                    ]
                ])
            ]
        ];
    }

    /**
     * Get Staff taskbar configuration
     */
    protected function getStaffTaskbar(?User $user): array
    {
        return [
            'primary' => [
                'name' => 'Core Functions',
                'icon' => 'fas fa-tachometer-alt',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Dashboard',
                        'route' => $this->getStaffDashboardRoute($user),
                        'icon' => 'fas fa-tachometer-alt',
                        'permission' => 'view_dashboard'
                    ],
                    [
                        'name' => 'Communication',
                        'route' => 'staff.communication',
                        'icon' => 'fas fa-comments',
                        'permission' => 'manage_communication'
                    ],
                    [
                        'name' => 'Reports',
                        'route' => 'staff.reports',
                        'icon' => 'fas fa-file-alt',
                        'permission' => 'view_reports'
                    ]
                ])
            ],
            'secondary' => [
                'name' => 'Specialized Tools',
                'icon' => 'fas fa-tools',
                'items' => $this->getStaffSpecializedTools($user)
            ]
        ];
    }

    /**
     * Get Player taskbar configuration
     */
    protected function getPlayerTaskbar(?User $user): array
    {
        return [
            'primary' => [
                'name' => 'Personal Management',
                'icon' => 'fas fa-user',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Profile & Progress',
                        'route' => 'player.portal.profile',
                        'icon' => 'fas fa-user-circle',
                        'permission' => 'view_profile'
                    ],
                    [
                        'name' => 'Schedule & Training',
                        'route' => 'player.portal.schedule',
                        'icon' => 'fas fa-calendar-check',
                        'permission' => 'view_schedule'
                    ],
                    [
                        'name' => 'Communication',
                        'route' => 'player.portal.communication',
                        'icon' => 'fas fa-comments',
                        'permission' => 'manage_communication'
                    ]
                ])
            ],
            'secondary' => [
                'name' => 'Academy Services',
                'icon' => 'fas fa-concierge-bell',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Documents & Forms',
                        'route' => 'player.portal.documents',
                        'icon' => 'fas fa-file-alt',
                        'permission' => 'view_documents'
                    ],
                    [
                        'name' => 'Payments & Billing',
                        'route' => 'player.portal.payments',
                        'icon' => 'fas fa-credit-card',
                        'permission' => 'view_payments'
                    ],
                    [
                        'name' => 'Support & Resources',
                        'route' => 'player.portal.support',
                        'icon' => 'fas fa-life-ring',
                        'permission' => 'view_support'
                    ]
                ])
            ]
        ];
    }

    /**
     * Get Parent taskbar configuration
     */
    protected function getParentTaskbar(?User $user): array
    {
        return [
            'primary' => [
                'name' => 'Child Management',
                'icon' => 'fas fa-child',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Player Profile',
                        'route' => 'parent.player-profile',
                        'icon' => 'fas fa-user-circle',
                        'permission' => 'view_player_profile'
                    ],
                    [
                        'name' => 'Training Progress',
                        'route' => 'parent.training',
                        'icon' => 'fas fa-chart-line',
                        'permission' => 'view_training'
                    ],
                    [
                        'name' => 'Matches & Events',
                        'route' => 'parent.matches',
                        'icon' => 'fas fa-futbol',
                        'permission' => 'view_matches'
                    ]
                ])
            ],
            'secondary' => [
                'name' => 'Academy Services',
                'icon' => 'fas fa-concierge-bell',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Media & Photos',
                        'route' => 'parent.media',
                        'icon' => 'fas fa-camera',
                        'permission' => 'view_media'
                    ],
                    [
                        'name' => 'Insights & Analytics',
                        'route' => 'parent.insights',
                        'icon' => 'fas fa-chart-bar',
                        'permission' => 'view_insights'
                    ],
                    [
                        'name' => 'Announcements',
                        'route' => 'parent.announcements',
                        'icon' => 'fas fa-bullhorn',
                        'permission' => 'view_announcements'
                    ]
                ])
            ]
        ];
    }

    /**
     * Get Trial taskbar configuration
     */
    protected function getTrialTaskbar(?User $user): array
    {
        return [
            'primary' => [
                'name' => 'Basic Features',
                'icon' => 'fas fa-star',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Dashboard Overview',
                        'route' => 'trial.dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'permission' => 'view_dashboard'
                    ],
                    [
                        'name' => 'Limited Player Management',
                        'route' => 'trial.players',
                        'icon' => 'fas fa-users',
                        'permission' => 'view_players'
                    ]
                ])
            ],
            'secondary' => [
                'name' => 'Trial Tools',
                'icon' => 'fas fa-clock',
                'items' => $this->filterItemsByPermission($user, [
                    [
                        'name' => 'Feature Demonstrations',
                        'route' => 'trial.demos',
                        'icon' => 'fas fa-play-circle',
                        'permission' => 'view_demos'
                    ],
                    [
                        'name' => 'Upgrade Options',
                        'route' => 'trial.upgrade',
                        'icon' => 'fas fa-arrow-up',
                        'permission' => 'view_upgrade'
                    ]
                ])
            ]
        ];
    }

    /**
     * Filter taskbar items based on user permissions
     * Uses RoleDisplayService for clean separation
     */
    protected function filterItemsByPermission(?User $user, array $items): array
    {
        // If no user, show nothing
        if (!$user) {
            return [];
        }

        // Use RoleDisplayService for filtering
        return $this->roleDisplayService->filterTaskbarItemsByPermission($user, $items);
    }

    /**
     * Get staff specialized tools based on role
     */
    protected function getStaffSpecializedTools(?User $user): array
    {
        // If no user, return empty
        if (!$user) {
            return [];
        }

        $tools = [];

        // Coach tools
        if ($user->hasAnyRole(['coach', 'assistant-coach', 'head-coach'])) {
            $tools = array_merge($tools, [
                [
                    'name' => 'Training Management',
                    'route' => 'coach.training-sessions',
                    'icon' => 'fas fa-dumbbell',
                    'permission' => 'manage_training'
                ],
                [
                    'name' => 'Player Progress',
                    'route' => 'coach.player-progress',
                    'icon' => 'fas fa-chart-line',
                    'permission' => 'view_player_progress'
                ]
            ]);
        }

        // Manager tools
        if ($user->hasRole('team-manager')) {
            $tools = array_merge($tools, [
                [
                    'name' => 'Equipment Management',
                    'route' => 'manager.equipment',
                    'icon' => 'fas fa-box',
                    'permission' => 'manage_equipment'
                ],
                [
                    'name' => 'Logistics',
                    'route' => 'manager.logistics',
                    'icon' => 'fas fa-truck',
                    'permission' => 'manage_logistics'
                ]
            ]);
        }

        // Media tools
        if ($user->hasRole('media-officer')) {
            $tools = array_merge($tools, [
                [
                    'name' => 'Content Management',
                    'route' => 'media.content',
                    'icon' => 'fas fa-camera',
                    'permission' => 'manage_content'
                ]
            ]);
        }

        // Welfare tools
        if ($user->hasRole('safeguarding-officer')) {
            $tools = array_merge($tools, [
                [
                    'name' => 'Attention List',
                    'route' => 'welfare.attention-list',
                    'icon' => 'fas fa-exclamation-triangle',
                    'permission' => 'view_attention_list'
                ],
                [
                    'name' => 'Compliance Monitoring',
                    'route' => 'welfare.compliance',
                    'icon' => 'fas fa-shield-alt',
                    'permission' => 'view_compliance'
                ]
            ]);
        }

        // Finance tools
        if ($user->hasAnyRole(['finance-officer', 'finance-admin', 'operations-admin'])) {
            $tools = array_merge($tools, [
                [
                    'name' => 'Financial Dashboard',
                    'route' => 'finance.dashboard',
                    'icon' => 'fas fa-chart-bar',
                    'permission' => 'view_financials'
                ],
                [
                    'name' => 'Payment Processing',
                    'route' => 'finance.payments',
                    'icon' => 'fas fa-money-bill',
                    'permission' => 'manage_payments'
                ]
            ]);
        }

        return $this->filterItemsByPermission($user, $tools);
    }

    /**
     * Get staff dashboard route
     */
    protected function getStaffDashboardRoute(?User $user): string
    {
        if (!$user) {
            return 'staff.dashboard';
        }

        return $this->userTypeService->getStaffDashboardRoute($user);
    }

    /**
     * Check if user can access specific taskbar item
     * Delegates to RoleDisplayService for clean separation
     */
    public function canAccessTaskbarItem(User $user, string $permission): bool
    {
        return $this->roleDisplayService->canAccessTaskbarItem($user, $permission);
    }

    /**
     * Get dashboard breadcrumbs
     */
    public function getBreadcrumbs(User $user): array
    {
        $primaryRoleSlug = $this->roleDisplayService->getPrimaryRoleSlug($user);
        $userType = $primaryRoleSlug ? $this->mapRoleToUserType($primaryRoleSlug) : 'user';

        $breadcrumbs = [
            'Home' => route('dashboard'),
            $this->userTypeService->getUserTypeDisplayName($userType) => $this->userTypeService->getDashboardRouteByType($userType)
        ];

        return $breadcrumbs;
    }
}
