<?php

/**
 * Dashboard Configuration
 *
 * Defines dashboard layouts and their allowed roles.
 * This configuration controls:
 * - Which roles can access each dashboard
 * - Taskbar/menu configuration per dashboard
 * - Layout selection
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard Definitions
    |--------------------------------------------------------------------------
    |
    | Each dashboard defines:
    | - name: Display name
    | - route: Route name
    | - layout: Blade layout to use
    | - allowed_roles: Roles that can access this dashboard
    | - priority: Dashboard priority for role matching
    | - taskbar: Taskbar configuration
    |
    */
    'superadmin' => [
        'name' => 'Super Admin Dashboard',
        'route' => 'super-admin.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => ['super-admin'],
        'priority' => 100,
        'icon' => 'fas fa-crown',
        'taskbar' => 'taskbars.superadmin',
    ],

    'organization' => [
        'name' => 'Organization Dashboard',
        'route' => 'admin.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => [
            'org-admin',
            'admin-operations',
        ],
        'priority' => 90,
        'icon' => 'fas fa-building',
        'taskbar' => 'taskbars.organization',
    ],

    'staff' => [
        'name' => 'Staff Dashboard',
        'route' => 'staff.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => [
            'head-coach',
            'coach',
            'assistant-coach',
            'team-manager',
        ],
        'priority' => 60,
        'icon' => 'fas fa-users-cog',
        'taskbar' => 'taskbars.staff',
    ],

    'finance' => [
        'name' => 'Finance Dashboard',
        'route' => 'finance.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => [
            'finance-admin',
            'finance-officer',
        ],
        'priority' => 80,
        'icon' => 'fas fa-chart-line',
        'taskbar' => 'taskbars.finance',
    ],

    'media' => [
        'name' => 'Media Dashboard',
        'route' => 'media.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => [
            'media-officer',
        ],
        'priority' => 65,
        'icon' => 'fas fa-camera',
        'taskbar' => 'taskbars.media',
    ],

    'safeguarding' => [
        'name' => 'Safeguarding Dashboard',
        'route' => 'safeguarding.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => [
            'safeguarding-officer',
        ],
        'priority' => 70,
        'icon' => 'fas fa-shield-alt',
        'taskbar' => 'taskbars.safeguarding',
    ],

    'coach' => [
        'name' => 'Coach Dashboard',
        'route' => 'coach.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => [
            'head-coach',
            'coach',
            'assistant-coach',
        ],
        'priority' => 75,
        'icon' => 'fas fa-clipboard-list',
        'taskbar' => 'taskbars.coach',
    ],

    'player' => [
        'name' => 'Player Portal',
        'route' => 'player.portal.dashboard',
        'layout' => 'layouts.player',
        'allowed_roles' => ['player'],
        'priority' => 10,
        'icon' => 'fas fa-running',
        'taskbar' => 'taskbars.player',
    ],

    'parent' => [
        'name' => 'Parent Dashboard',
        'route' => 'parent.dashboard',
        'layout' => 'layouts.player',
        'allowed_roles' => ['parent'],
        'priority' => 20,
        'icon' => 'fas fa-users',
        'taskbar' => 'taskbars.parent',
    ],

    'partner' => [
        'name' => 'Partner Dashboard',
        'route' => 'partner.dashboard',
        'layout' => 'layouts.dashboard',
        'allowed_roles' => ['partner'],
        'priority' => 15,
        'icon' => 'fas fa-handshake',
        'taskbar' => 'taskbars.partner',
    ],

    'trial' => [
        'name' => 'Trial Dashboard',
        'route' => 'trial.dashboard',
        'layout' => 'layouts.player',
        'allowed_roles' => ['trial'],
        'priority' => 5,
        'icon' => 'fas fa-clock',
        'taskbar' => 'taskbars.trial',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Maps dashboard types to blade layouts
    |
    */
    'layouts' => [
        'academy' => 'layouts.academy',
        'dashboard' => 'layouts.dashboard',
        'player' => 'layouts.player',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Dashboard per Role
    |--------------------------------------------------------------------------
    |
    | Maps each role to its default dashboard
    |
    */
    'role_defaults' => [
        'super-admin' => 'superadmin',
        'org-admin' => 'organization',
        'admin-operations' => 'organization',
        'head-coach' => 'coach',
        'coach' => 'coach',
        'assistant-coach' => 'coach',
        'team-manager' => 'staff',
        'finance-admin' => 'finance',
        'finance-officer' => 'finance',
        'media-officer' => 'media',
        'safeguarding-officer' => 'safeguarding',
        'partner' => 'partner',
        'parent' => 'parent',
        'player' => 'player',
        'trial' => 'trial',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Hierarchy
    |--------------------------------------------------------------------------
    |
    | Order of priority when determining primary dashboard
    |
    */
    'hierarchy' => [
        'superadmin',
        'organization',
        'finance',
        'safeguarding',
        'coach',
        'media',
        'staff',
        'partner',
        'parent',
        'player',
        'trial',
    ],
];
