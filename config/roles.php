<?php

/**
 * Role Configuration for Mumias Vipers SaaS Platform
 *
 * Multi-tenant sports management platform role hierarchy.
 *
 * Role Levels:
 * - Platform: Super-admin level (system-wide control)
 * - Organization: Club/academy level (organization-wide control)
 * - Team: Team-specific roles
 * - Members: Player/parent accounts
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Role Priority Hierarchy
    |--------------------------------------------------------------------------
    |
    | Higher values = higher priority. The primary role is determined by
    | the highest priority role a user possesses.
    |
    | Priority determines which dashboard loads by default for users
    | with multiple roles.
    |
    */
    'priority' => [
        // Platform Level (100+)
        'super-admin' => 100,

        // Organization Level (50-99)
        'org-admin' => 90,
        'admin-operations' => 85,
        'finance-admin' => 80,
        'head-coach' => 75,
        'safeguarding-officer' => 70,
        'finance-officer' => 68,
        'media-officer' => 65,

        // Team Level (20-60)
        'coach' => 60,
        'assistant-coach' => 55,
        'team-manager' => 50,

        // Member Level (1-20)
        'partner' => 15,
        'parent' => 10,
        'player' => 5,
        'trial' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Levels
    |--------------------------------------------------------------------------
    |
    | Organize roles by their scope level in the platform.
    |
    */
    'levels' => [
        'platform' => [
            'super-admin',
        ],
        'organization' => [
            'org-admin',
            'admin-operations',
            'finance-admin',
            'head-coach',
            'safeguarding-officer',
            'finance-officer',
            'media-officer',
        ],
        'team' => [
            'coach',
            'assistant-coach',
            'team-manager',
        ],
        'member' => [
            'partner',
            'parent',
            'player',
            'trial',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Roles (Can Override Dashboard)
    |--------------------------------------------------------------------------
    |
    | Roles that can change a user's dashboard override.
    |
    */
    'admin_roles' => [
        'super-admin',
        'org-admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Required for Elevated Role Display
    |--------------------------------------------------------------------------
    |
    | The permission name required to view elevated role indicators.
    |
    */
    'elevated_display_permission' => 'view_elevated_roles',

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | User Dashboard Override Column
    |--------------------------------------------------------------------------
    |
    | Database column name for dashboard role override.
    |
    */
    'override_column' => 'dashboard_role_override',
];
