<?php

return [
    'taskbars' => [
        'superadmin' => [
            'name' => 'System',
            'icon' => 'fas fa-cog',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'super-admin.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Organizations',
                    'icon' => 'fas fa-building',
                    'route' => 'super-admin.organizations.index',
                    'permission' => 'organizations.view',
                ],
                [
                    'name' => 'Users',
                    'icon' => 'fas fa-users',
                    'route' => 'super-admin.users.index',
                    'permission' => 'users.view',
                ],
                [
                    'name' => 'Roles & Permissions',
                    'icon' => 'fas fa-user-shield',
                    'route' => 'super-admin.roles.index',
                    'permission' => 'roles.view',
                ],
                [
                    'name' => 'Subscription Plans',
                    'icon' => 'fas fa-tags',
                    'route' => 'super-admin.plans.index',
                    'permission' => 'plans.view',
                ],
                [
                    'name' => 'System Settings',
                    'icon' => 'fas fa-cog',
                    'route' => 'super-admin.settings',
                    'permission' => 'settings.view',
                ],
            ],
        ],

        'organization' => [
            'name' => 'Organization',
            'icon' => 'fas fa-building',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'admin.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Players',
                    'icon' => 'fas fa-users',
                    'route' => 'admin.players.index',
                    'permission' => 'players.view',
                ],
                [
                    'name' => 'Attendance',
                    'icon' => 'fas fa-calendar-check',
                    'route' => 'admin.attendance.index',
                    'permission' => 'attendance.view',
                ],
                [
                    'name' => 'Training',
                    'icon' => 'fas fa-stopwatch',
                    'route' => 'admin.training-sessions.index',
                    'permission' => 'training.view',
                ],
                [
                    'name' => 'Statistics',
                    'icon' => 'fas fa-chart-bar',
                    'route' => 'admin.game-statistics.index',
                    'permission' => 'statistics.view',
                ],
                [
                    'name' => 'Programs',
                    'icon' => 'fas fa-graduation-cap',
                    'route' => 'admin.programs.index',
                    'permission' => 'programs.view',
                ],
                [
                    'name' => 'Teams',
                    'icon' => 'fas fa-shield-alt',
                    'route' => 'admin.teams.index',
                    'permission' => 'teams.view',
                ],
                [
                    'name' => 'Staff',
                    'icon' => 'fas fa-users-cog',
                    'route' => 'admin.staff.index',
                    'permission' => 'staff.view',
                ],
                [
                    'name' => 'Partners',
                    'icon' => 'fas fa-handshake',
                    'route' => 'admin.partners.index',
                    'permission' => 'partners.view',
                ],
                [
                    'name' => 'Equipment',
                    'icon' => 'fas fa-boxes',
                    'route' => 'admin.equipment.categories',
                    'permission' => 'equipment.view',
                ],
                [
                    'name' => 'Analytics',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'admin.analytics',
                    'permission' => 'analytics.view',
                ],
                [
                    'name' => 'Compliance',
                    'icon' => 'fas fa-shield-alt',
                    'route' => 'admin.compliance.report',
                    'permission' => 'compliance.view',
                ],
                [
                    'name' => 'Careers',
                    'icon' => 'fas fa-briefcase',
                    'route' => 'admin.careers.index',
                    'permission' => 'careers.view',
                ],
                [
                    'name' => 'Documents',
                    'icon' => 'fas fa-file-alt',
                    'route' => 'admin.documents.index',
                    'permission' => 'documents.view',
                ],
                [
                    'name' => 'Announcements',
                    'icon' => 'fas fa-bullhorn',
                    'route' => 'admin.announcements.index',
                    'permission' => 'announcements.view',
                ],
                [
                    'name' => 'Messaging',
                    'icon' => 'fas fa-comments',
                    'route' => 'admin.messaging.index',
                    'permission' => 'messaging.view',
                ],
            ],
        ],

        'finance' => [
            'name' => 'Finance',
            'icon' => 'fas fa-credit-card',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'finance.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Payments',
                    'icon' => 'fas fa-money-bill-wave',
                    'route' => 'finance.payments',
                    'permission' => 'payments.view',
                ],
                [
                    'name' => 'Expenses',
                    'icon' => 'fas fa-receipt',
                    'route' => 'finance.expenses.index',
                    'permission' => 'expenses.view',
                ],
                [
                    'name' => 'Budgets',
                    'icon' => 'fas fa-calculator',
                    'route' => 'finance.budgets.index',
                    'permission' => 'finance.budgets',
                ],
                [
                    'name' => 'Reports',
                    'icon' => 'fas fa-chart-pie',
                    'route' => 'finance.reports',
                    'permission' => 'finance.view',
                ],
            ],
        ],

        'coach' => [
            'name' => 'Coaching',
            'icon' => 'fas fa-chalkboard-teacher',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'coach.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Training Sessions',
                    'icon' => 'fas fa-stopwatch',
                    'route' => 'coach.sessions',
                    'permission' => 'training.view',
                ],
                [
                    'name' => 'Players',
                    'icon' => 'fas fa-users',
                    'route' => 'coach.players',
                    'permission' => 'players.view',
                ],
                [
                    'name' => 'Statistics',
                    'icon' => 'fas fa-chart-bar',
                    'route' => 'coach.statistics',
                    'permission' => 'statistics.view',
                ],
                [
                    'name' => 'Attendance',
                    'icon' => 'fas fa-calendar-check',
                    'route' => 'coach.attendance',
                    'permission' => 'attendance.view',
                ],
            ],
        ],

        'player' => [
            'name' => 'My Portal',
            'icon' => 'fas fa-user-circle',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'player.portal.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Programs',
                    'icon' => 'fas fa-graduation-cap',
                    'route' => 'player.portal.programs',
                    'permission' => 'programs.view',
                ],
                [
                    'name' => 'Training',
                    'icon' => 'fas fa-running',
                    'route' => 'player.portal.training',
                    'permission' => 'training.view',
                ],
                [
                    'name' => 'Statistics',
                    'icon' => 'fas fa-chart-bar',
                    'route' => 'player.portal.statistics',
                    'permission' => 'statistics.view',
                ],
                [
                    'name' => 'Attendance',
                    'icon' => 'fas fa-calendar-check',
                    'route' => 'player.portal.attendance',
                    'permission' => 'attendance.view',
                ],
            ],
        ],

        'parent' => [
            'name' => 'Parent Portal',
            'icon' => 'fas fa-users',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'parent.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Child Progress',
                    'icon' => 'fas fa-child',
                    'route' => 'parent.children',
                    'permission' => 'players.view',
                ],
                [
                    'name' => 'Training',
                    'icon' => 'fas fa-running',
                    'route' => 'parent.training',
                    'permission' => 'training.view',
                ],
                [
                    'name' => 'Statistics',
                    'icon' => 'fas fa-chart-bar',
                    'route' => 'parent.statistics',
                    'permission' => 'statistics.view',
                ],
                [
                    'name' => 'Attendance',
                    'icon' => 'fas fa-calendar-check',
                    'route' => 'parent.attendance',
                    'permission' => 'attendance.view',
                ],
            ],
        ],

        'partner' => [
            'name' => 'Partner Portal',
            'icon' => 'fas fa-handshake',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'partner.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Player Analytics',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'partner.analytics',
                    'permission' => 'analytics.view',
                ],
                [
                    'name' => 'Reports',
                    'icon' => 'fas fa-file-alt',
                    'route' => 'partner.reports',
                    'permission' => 'analytics.view',
                ],
            ],
        ],

        'trial' => [
            'name' => 'Trial',
            'icon' => 'fas fa-clock',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-th-large',
                    'route' => 'trial.dashboard',
                    'permission' => 'dashboard.view',
                ],
                [
                    'name' => 'Limited Features',
                    'icon' => 'fas fa-info-circle',
                    'route' => 'trial.features',
                    'permission' => 'dashboard.view',
                ],
            ],
        ],
    ],
];
