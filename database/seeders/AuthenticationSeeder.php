<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class AuthenticationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder consolidates roles, permissions, and test users into one file.
     * Run with: php artisan db:seed --class=AuthenticationSeeder
     */
    public function run(): void
    {
        $this->command->info('===========================================');
        $this->command->info('Starting Authentication Seeder...');
        $this->command->info('===========================================');

        // Step 1: Seed Roles
        $this->seedRoles();

        // Step 2: Seed Permissions
        $this->seedPermissions();

        // Step 3: Assign Permissions to Roles
        $this->assignPermissionsToRoles();

        // Step 4: Create Test Users
        $this->createTestUsers();

        $this->command->info('===========================================');
        $this->command->info('Authentication Seeder Complete!');
        $this->command->info('===========================================');

        // Summary
        $this->command->info('Roles: ' . Role::count());
        $this->command->info('Permissions: ' . Permission::count());
        $this->command->info('Users: ' . User::count());

        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('  superadmin@mumiasvipers.com (Super Admin) - password: password');
        $this->command->info('  admin@vipers.com (Admin Operations) - password: password');
        $this->command->info('  headcoach@vipers.com (Head Coach) - password: password');
        $this->command->info('  coach@vipers.com (Assistant Coach) - password: password');
        $this->command->info('  manager@vipers.com (Team Manager) - password: password');
        $this->command->info('  player@vipers.com (Player) - password: password');
        $this->command->info('  parent@vipers.com (Parent) - password: password');
        $this->command->info('  partner@vipers.com (Partner) - password: password');
    }

    /**
     * Step 1: Seed Roles
     */
    protected function seedRoles(): void
    {
        $this->command->info('');
        $this->command->info('Seeding Roles...');

        $roles = [
            // Admin Roles
            [
                'slug' => 'super-admin',
                'name' => 'Super Admin',
                'description' => 'Full system control with all permissions',
                'type' => 'admin',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'org-admin',
                'name' => 'Organization Admin',
                'description' => 'Full control over their organization',
                'type' => 'organization',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'admin-operations',
                'name' => 'Admin/Operations Manager',
                'description' => 'High-level administrative access without deletion capabilities',
                'type' => 'admin',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'head-coach',
                'name' => 'Head Coach',
                'description' => 'Complete coaching management across all teams',
                'type' => 'admin',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            // Partner Staff Roles
            [
                'slug' => 'coach',
                'name' => 'Coach',
                'description' => 'Primary coach role for team management',
                'type' => 'partner_staff',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'assistant-coach',
                'name' => 'Assistant Coach',
                'description' => 'Session-based operations: start sessions, mark attendance, manage assigned team',
                'type' => 'partner_staff',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'team-manager',
                'name' => 'Team Manager',
                'description' => 'Team logistics, coordination, and administrative support',
                'type' => 'partner_staff',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'safeguarding-officer',
                'name' => 'Safeguarding/Welfare Officer',
                'description' => 'Child protection, welfare monitoring, and safeguarding compliance',
                'type' => 'admin',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'finance-officer',
                'name' => 'Finance Officer',
                'description' => 'Financial management, payments, and reporting',
                'type' => 'admin',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'media-officer',
                'name' => 'Media & Communications Officer',
                'description' => 'Content creation, media management, and communications',
                'type' => 'admin',
                'is_default' => false,
                'is_system' => true,
                'is_active' => true,
            ],
            // Player/Parent Roles
            [
                'slug' => 'player',
                'name' => 'Player',
                'description' => 'Player portal access for training data and academy information',
                'type' => 'player',
                'is_default' => true,
                'is_system' => false,
                'is_active' => true,
            ],
            [
                'slug' => 'parent',
                'name' => 'Parent',
                'description' => 'Access to own child\'s information and academy communications',
                'type' => 'player',
                'is_default' => false,
                'is_system' => false,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        $this->command->info('Created ' . count($roles) . ' roles.');
    }

    /**
     * Step 2: Seed Permissions
     */
    protected function seedPermissions(): void
    {
        $this->command->info('');
        $this->command->info('Seeding Permissions...');

        // Clear existing permissions to ensure clean slate
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Permission::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $permissions = [
            // Matches
            ['name' => 'View Matches', 'slug' => 'matches.view', 'module' => 'matches'],
            ['name' => 'Create Matches', 'slug' => 'matches.create', 'module' => 'matches'],
            ['name' => 'Edit Matches', 'slug' => 'matches.edit', 'module' => 'matches'],
            ['name' => 'Delete Matches', 'slug' => 'matches.delete', 'module' => 'matches'],

            // Documents
            ['name' => 'View Documents', 'slug' => 'documents.view', 'module' => 'documents'],
            ['name' => 'Upload Documents', 'slug' => 'documents.upload', 'module' => 'documents'],
            ['name' => 'Approve Documents', 'slug' => 'documents.approve', 'module' => 'documents'],

            // Jobs
            ['name' => 'View Jobs', 'slug' => 'jobs.view', 'module' => 'jobs'],
            ['name' => 'Create Jobs', 'slug' => 'jobs.create', 'module' => 'jobs'],
            ['name' => 'Edit Jobs', 'slug' => 'jobs.edit', 'module' => 'jobs'],
            ['name' => 'Delete Jobs', 'slug' => 'jobs.delete', 'module' => 'jobs'],

            // Attendance
            ['name' => 'Clock Player In', 'slug' => 'attendance.clock_in', 'module' => 'attendance'],
            ['name' => 'Clock Player Out', 'slug' => 'attendance.clock_out', 'module' => 'attendance'],
            ['name' => 'Mark Attendance', 'slug' => 'attendance.mark', 'module' => 'attendance'],
            ['name' => 'View Attendance History', 'slug' => 'attendance.view', 'module' => 'attendance'],

            // Communication
            ['name' => 'Send Bulk Messages', 'slug' => 'communication.bulk', 'module' => 'communication'],
            ['name' => 'Send Team Messages', 'slug' => 'communication.team', 'module' => 'communication'],
            ['name' => 'Approve Announcements', 'slug' => 'communication.approve', 'module' => 'communication'],

            // Content
            ['name' => 'View News', 'slug' => 'content.news.view', 'module' => 'content'],
            ['name' => 'Create News', 'slug' => 'content.news.create', 'module' => 'content'],
            ['name' => 'Edit News', 'slug' => 'content.news.edit', 'module' => 'content'],
            ['name' => 'Delete News', 'slug' => 'content.news.delete', 'module' => 'content'],

            // Finance
            ['name' => 'View Payments', 'slug' => 'finance.payments.view', 'module' => 'finance'],
            ['name' => 'Process Payments', 'slug' => 'finance.payments.process', 'module' => 'finance'],
            ['name' => 'View Financial Reports', 'slug' => 'finance.reports.view', 'module' => 'finance'],

            // Players
            ['name' => 'View Players', 'slug' => 'players.view', 'module' => 'players'],
            ['name' => 'Create Players', 'slug' => 'players.create', 'module' => 'players'],
            ['name' => 'Edit Players', 'slug' => 'players.edit', 'module' => 'players'],
            ['name' => 'Delete Players', 'slug' => 'players.delete', 'module' => 'players'],
            ['name' => 'Approve Players', 'slug' => 'players.approve', 'module' => 'players'],
            ['name' => 'View Player Portal', 'slug' => 'players.portal.view', 'module' => 'players'],
            ['name' => 'Update Player Profile', 'slug' => 'players.profile.update', 'module' => 'players'],
            ['name' => 'View Training Data', 'slug' => 'players.training.view', 'module' => 'players'],

            // Partners
            ['name' => 'View Partners', 'slug' => 'partners.view', 'module' => 'partners'],
            ['name' => 'Create Partners', 'slug' => 'partners.create', 'module' => 'partners'],
            ['name' => 'Edit Partners', 'slug' => 'partners.edit', 'module' => 'partners'],
            ['name' => 'Delete Partners', 'slug' => 'partners.delete', 'module' => 'partners'],
            ['name' => 'Approve Partners', 'slug' => 'partners.approve', 'module' => 'partners'],
            ['name' => 'View Partner Analytics', 'slug' => 'partners.analytics.view', 'module' => 'partners'],
            ['name' => 'Create Staff Accounts', 'slug' => 'partners.staff.create', 'module' => 'partners'],
            ['name' => 'Manage Staff Roles', 'slug' => 'partners.staff.roles', 'module' => 'partners'],

            // Orders
            ['name' => 'View Orders', 'slug' => 'orders.view', 'module' => 'orders'],
            ['name' => 'Process Orders', 'slug' => 'orders.process', 'module' => 'orders'],
            ['name' => 'Manage Order Status', 'slug' => 'orders.status.manage', 'module' => 'orders'],

            // Programs
            ['name' => 'View Programs', 'slug' => 'programs.view', 'module' => 'programs'],
            ['name' => 'Create Programs', 'slug' => 'programs.create', 'module' => 'programs'],
            ['name' => 'Edit Programs', 'slug' => 'programs.edit', 'module' => 'programs'],
            ['name' => 'Delete Programs', 'slug' => 'programs.delete', 'module' => 'programs'],

            // Reports
            ['name' => 'Generate Reports', 'slug' => 'reports.generate', 'module' => 'reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'module' => 'reports'],

            // Sessions
            ['name' => 'Start Training Session', 'slug' => 'sessions.start', 'module' => 'sessions'],
            ['name' => 'End Training Session', 'slug' => 'sessions.end', 'module' => 'sessions'],
            ['name' => 'Add Session Notes', 'slug' => 'sessions.notes.add', 'module' => 'sessions'],

            // Statistics
            ['name' => 'View Statistics', 'slug' => 'statistics.view', 'module' => 'statistics'],
            ['name' => 'Create Statistics', 'slug' => 'statistics.create', 'module' => 'statistics'],
            ['name' => 'Edit Statistics', 'slug' => 'statistics.edit', 'module' => 'statistics'],
            ['name' => 'Delete Statistics', 'slug' => 'statistics.delete', 'module' => 'statistics'],

            // System
            ['name' => 'View System Logs', 'slug' => 'system.logs.view', 'module' => 'system'],
            ['name' => 'Manage System Settings', 'slug' => 'system.settings.manage', 'module' => 'system'],
            ['name' => 'Manage Roles & Permissions', 'slug' => 'system.rbac.manage', 'module' => 'system'],

            // Teams
            ['name' => 'Create Team', 'slug' => 'teams.create', 'module' => 'teams'],
            ['name' => 'Edit Team', 'slug' => 'teams.edit', 'module' => 'teams'],
            ['name' => 'Assign Players to Team', 'slug' => 'teams.players.assign', 'module' => 'teams'],

            // Organizations
            ['name' => 'View Organizations', 'slug' => 'organizations.view', 'module' => 'organizations'],
            ['name' => 'Create Organizations', 'slug' => 'organizations.create', 'module' => 'organizations'],
            ['name' => 'Edit Organizations', 'slug' => 'organizations.edit', 'module' => 'organizations'],
            ['name' => 'Delete Organizations', 'slug' => 'organizations.delete', 'module' => 'organizations'],
            ['name' => 'Manage Organizations', 'slug' => 'manage_organizations', 'module' => 'organizations'],

            // Users
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users'],
            ['name' => 'Approve Users', 'slug' => 'users.approve', 'module' => 'users'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        $this->command->info('Created ' . count($permissions) . ' permissions.');
    }

    /**
     * Step 3: Assign Permissions to Roles
     */
    protected function assignPermissionsToRoles(): void
    {
        $this->command->info('');
        $this->command->info('Assigning Permissions to Roles...');

        // Super Admin - All permissions
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->permissions()->sync($allPermissions->pluck('id'));
            $this->command->info('Assigned all permissions to Super Admin');
        }

        // Organization Admin
        $orgAdmin = Role::where('slug', 'org-admin')->first();
        if ($orgAdmin) {
            $permissions = Permission::whereIn('slug', [
                'organizations.view', 'organizations.create', 'organizations.edit', 'organizations.delete', 'manage_organizations',
                'players.view', 'players.create', 'players.edit', 'players.approve',
                'programs.view', 'programs.create', 'programs.edit',
                'documents.view', 'documents.upload', 'documents.approve',
                'orders.view', 'orders.process', 'orders.status.manage',
                'finance.payments.view', 'finance.reports.view',
                'teams.create', 'teams.edit', 'teams.players.assign',
                'users.view', 'users.create', 'users.edit', 'users.approve',
                'content.news.view', 'content.news.create', 'content.news.edit',
                'attendance.view', 'attendance.mark', 'attendance.clock_in', 'attendance.clock_out',
                'sessions.start', 'sessions.end', 'sessions.notes.add',
                'communication.bulk', 'communication.team',
                'reports.generate', 'reports.export',
            ])->get();
            $orgAdmin->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Org Admin');
        }

        // Admin/Operations Manager
        $adminOps = Role::where('slug', 'admin-operations')->first();
        if ($adminOps) {
            $permissions = Permission::whereIn('slug', [
                'users.view', 'users.create', 'users.edit', 'users.approve',
                'players.view', 'players.create', 'players.edit', 'players.approve',
                'programs.view', 'programs.create',
                'documents.view', 'documents.upload', 'documents.approve', 'programs.edit',
                'orders.view', 'orders.process', 'orders.status.manage',
                'system.logs.view', 'system.settings.manage',
            ])->get();
            $adminOps->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Admin Operations');
        }

        // Head Coach
        $headCoach = Role::where('slug', 'head-coach')->first();
        if ($headCoach) {
            $permissions = Permission::whereIn('slug', [
                'players.view', 'players.edit',
                'statistics.view', 'statistics.create', 'statistics.edit',
                'programs.view', 'programs.create', 'programs.edit',
                'matches.view', 'matches.create', 'matches.edit',
                'sessions.start', 'sessions.end', 'sessions.notes.add',
                'attendance.mark', 'attendance.clock_in', 'attendance.clock_out', 'attendance.view',
                'teams.create', 'teams.edit', 'teams.players.assign',
                'players.training.view',
            ])->get();
            $headCoach->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Head Coach');
        }

        // Coach
        $coach = Role::where('slug', 'coach')->first();
        if ($coach) {
            $permissions = Permission::whereIn('slug', [
                'sessions.start', 'sessions.end', 'sessions.notes.add',
                'attendance.mark', 'attendance.clock_in', 'attendance.clock_out', 'attendance.view',
                'players.view', 'players.training.view',
                'statistics.view', 'statistics.create',
                'programs.view',
            ])->get();
            $coach->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Coach');
        }

        // Assistant Coach
        $assistantCoach = Role::where('slug', 'assistant-coach')->first();
        if ($assistantCoach) {
            $permissions = Permission::whereIn('slug', [
                'sessions.start', 'sessions.end', 'sessions.notes.add',
                'attendance.mark', 'attendance.clock_in', 'attendance.clock_out', 'attendance.view',
                'players.view',
                'players.training.view',
            ])->get();
            $assistantCoach->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Assistant Coach');
        }

        // Team Manager
        $teamManager = Role::where('slug', 'team-manager')->first();
        if ($teamManager) {
            $permissions = Permission::whereIn('slug', [
                'players.view', 'players.edit',
                'programs.view', 'programs.edit',
                'orders.view', 'orders.process',
                'documents.view', 'documents.upload',
                'communication.team',
                'reports.generate', 'reports.export',
            ])->get();
            $teamManager->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Team Manager');
        }

        // Safeguarding Officer
        $safeguarding = Role::where('slug', 'safeguarding-officer')->first();
        if ($safeguarding) {
            $permissions = Permission::whereIn('slug', [
                'players.view', 'players.edit',
                'documents.view', 'documents.upload', 'documents.approve',
                'system.logs.view',
                'communication.bulk', 'communication.approve',
            ])->get();
            $safeguarding->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Safeguarding Officer');
        }

        // Finance Officer
        $financeOfficer = Role::where('slug', 'finance-officer')->first();
        if ($financeOfficer) {
            $permissions = Permission::whereIn('slug', [
                'finance.payments.view', 'finance.payments.process', 'finance.reports.view',
                'orders.view', 'orders.status.manage',
                'reports.generate', 'reports.export',
            ])->get();
            $financeOfficer->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Finance Officer');
        }

        // Media Officer
        $mediaOfficer = Role::where('slug', 'media-officer')->first();
        if ($mediaOfficer) {
            $permissions = Permission::whereIn('slug', [
                'content.news.view', 'content.news.create', 'content.news.edit',
                'communication.bulk', 'communication.team', 'communication.approve',
            ])->get();
            $mediaOfficer->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Media Officer');
        }

        // Player
        $player = Role::where('slug', 'player')->first();
        if ($player) {
            $permissions = Permission::whereIn('slug', [
                'players.portal.view',
                'players.profile.update',
                'players.training.view',
                'documents.upload',
            ])->get();
            $player->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Player');
        }

        // Parent
        $parent = Role::where('slug', 'parent')->first();
        if ($parent) {
            $permissions = Permission::whereIn('slug', [
                'players.portal.view',
                'players.training.view',
            ])->get();
            $parent->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned permissions to Parent');
        }
    }

    /**
     * Step 4: Create Test Users
     */
    protected function createTestUsers(): void
    {
        $this->command->info('');
        $this->command->info('Creating Test Users...');

        $users = [
            // Super Admin
            [
                'name' => 'Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@mumiasvipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'super-admin',
            ],
            // Admin Operations
            [
                'name' => 'Admin Operations',
                'first_name' => 'Admin',
                'last_name' => 'Operations',
                'email' => 'admin@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'admin-operations',
            ],
            // Head Coach
            [
                'name' => 'Head Coach',
                'first_name' => 'Head',
                'last_name' => 'Coach',
                'email' => 'headcoach@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'head-coach',
            ],
            // Coach
            [
                'name' => 'Team Coach',
                'first_name' => 'Team',
                'last_name' => 'Coach',
                'email' => 'coach@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'coach',
            ],
            // Assistant Coach
            [
                'name' => 'Assistant Coach',
                'first_name' => 'Assistant',
                'last_name' => 'Coach',
                'email' => 'assistantcoach@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'assistant-coach',
            ],
            // Team Manager
            [
                'name' => 'Team Manager',
                'first_name' => 'Team',
                'last_name' => 'Manager',
                'email' => 'manager@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'team-manager',
            ],
            // Safeguarding Officer
            [
                'name' => 'Safeguarding Officer',
                'first_name' => 'Safeguarding',
                'last_name' => 'Officer',
                'email' => 'safeguarding@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'safeguarding-officer',
            ],
            // Finance Officer
            [
                'name' => 'Finance Officer',
                'first_name' => 'Finance',
                'last_name' => 'Officer',
                'email' => 'finance@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'finance-officer',
            ],
            // Media Officer
            [
                'name' => 'Media Officer',
                'first_name' => 'Media',
                'last_name' => 'Officer',
                'email' => 'media@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'media-officer',
            ],
            // Player
            [
                'name' => 'Test Player',
                'first_name' => 'Test',
                'last_name' => 'Player',
                'email' => 'player@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'player',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'player',
            ],
            // Parent
            [
                'name' => 'Test Parent',
                'first_name' => 'Test',
                'last_name' => 'Parent',
                'email' => 'parent@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'player',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => 'parent',
            ],
            // Partner
            [
                'name' => 'Partner Organization',
                'first_name' => 'Partner',
                'last_name' => 'Organization',
                'email' => 'partner@vipers.com',
                'password' => bcrypt('password'),
                'user_type' => 'partner',
                'approval_status' => 'approved',
                'status' => 'active',
                'role' => null, // Partners don't use role-based access in the same way
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($role) {
                $roleModel = Role::where('slug', $role)->first();
                if ($roleModel) {
                    $user->roles()->sync([$roleModel->id]);
                }
            }
        }

        $this->command->info('Created ' . count($users) . ' test users.');
    }
}
