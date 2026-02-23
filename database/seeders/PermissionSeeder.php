<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Standardized permission format: category.action (lowercase, dot notation)
     * 17 Categories:
     * 1. matches, 2. documents, 3. jobs, 4. attendance, 5. communication
     * 6. content, 7. finance, 8. players, 9. partners, 10. orders
     * 11. programs, 12. reports, 13. sessions, 14. statistics, 15. system
     * 16. teams, 17. users
     */
    public function run(): void
    {
        // Clear existing permissions to ensure clean slate
        Permission::truncate();

        // =====================================================================
        // 1. MATCHES (Order 1)
        // =====================================================================
        $matches = [
            ['name' => 'View Matches', 'slug' => 'matches.view'],
            ['name' => 'Create Matches', 'slug' => 'matches.create'],
            ['name' => 'Edit Matches', 'slug' => 'matches.edit'],
            ['name' => 'Delete Matches', 'slug' => 'matches.delete'],
        ];

        // =====================================================================
        // 2. DOCUMENTS (Order 2)
        // =====================================================================
        $documents = [
            ['name' => 'View Documents', 'slug' => 'documents.view'],
            ['name' => 'Upload Documents', 'slug' => 'documents.upload'],
            ['name' => 'Approve Documents', 'slug' => 'documents.approve'],
        ];

        // =====================================================================
        // 3. JOBS (Order 3)
        // =====================================================================
        $jobs = [
            ['name' => 'View Jobs', 'slug' => 'jobs.view'],
            ['name' => 'Create Jobs', 'slug' => 'jobs.create'],
            ['name' => 'Edit Jobs', 'slug' => 'jobs.edit'],
            ['name' => 'Delete Jobs', 'slug' => 'jobs.delete'],
        ];

        // =====================================================================
        // 4. ATTENDANCE (Order 4)
        // =====================================================================
        $attendance = [
            ['name' => 'Clock Player In', 'slug' => 'attendance.clock_in'],
            ['name' => 'Clock Player Out', 'slug' => 'attendance.clock_out'],
            ['name' => 'Mark Attendance', 'slug' => 'attendance.mark'],
            ['name' => 'View Attendance History', 'slug' => 'attendance.view'],
        ];

        // =====================================================================
        // 5. COMMUNICATION (Order 5)
        // =====================================================================
        $communication = [
            ['name' => 'Send Bulk Messages', 'slug' => 'communication.bulk'],
            ['name' => 'Send Team Messages', 'slug' => 'communication.team'],
            ['name' => 'Approve Announcements', 'slug' => 'communication.approve'],
        ];

        // =====================================================================
        // 6. CONTENT (Order 6)
        // =====================================================================
        $content = [
            // News
            ['name' => 'View News', 'slug' => 'content.news.view'],
            ['name' => 'Create News', 'slug' => 'content.news.create'],
            ['name' => 'Edit News', 'slug' => 'content.news.edit'],
            ['name' => 'Delete News', 'slug' => 'content.news.delete'],
            // Gallery
            ['name' => 'View Gallery', 'slug' => 'content.gallery.view'],
            ['name' => 'Create Gallery', 'slug' => 'content.gallery.create'],
            ['name' => 'Edit Gallery', 'slug' => 'content.gallery.edit'],
            ['name' => 'Delete Gallery', 'slug' => 'content.gallery.delete'],
        ];

        // =====================================================================
        // 7. FINANCE (Order 7)
        // =====================================================================
        $finance = [
            ['name' => 'View Payments', 'slug' => 'finance.payments.view'],
            ['name' => 'Process Payments', 'slug' => 'finance.payments.process'],
            ['name' => 'View Financial Reports', 'slug' => 'finance.reports.view'],
        ];

        // =====================================================================
        // 8. PLAYERS (Order 8)
        // =====================================================================
        $players = [
            ['name' => 'View Players', 'slug' => 'players.view'],
            ['name' => 'Create Players', 'slug' => 'players.create'],
            ['name' => 'Edit Players', 'slug' => 'players.edit'],
            ['name' => 'Delete Players', 'slug' => 'players.delete'],
            ['name' => 'Approve Players', 'slug' => 'players.approve'],
            ['name' => 'View Player Portal', 'slug' => 'players.portal.view'],
            ['name' => 'Update Player Profile', 'slug' => 'players.profile.update'],
            ['name' => 'View Training Data', 'slug' => 'players.training.view'],
        ];

        // =====================================================================
        // 9. PARTNERS (Order 9)
        // =====================================================================
        $partners = [
            ['name' => 'View Partners', 'slug' => 'partners.view'],
            ['name' => 'Create Partners', 'slug' => 'partners.create'],
            ['name' => 'Edit Partners', 'slug' => 'partners.edit'],
            ['name' => 'Delete Partners', 'slug' => 'partners.delete'],
            ['name' => 'Approve Partners', 'slug' => 'partners.approve'],
            ['name' => 'View Partner Analytics', 'slug' => 'partners.analytics.view'],
            ['name' => 'Create Staff Accounts', 'slug' => 'partners.staff.create'],
            ['name' => 'Manage Staff Roles', 'slug' => 'partners.staff.roles'],
        ];

        // =====================================================================
        // 10. ORDERS (Order 10)
        // =====================================================================
        $orders = [
            ['name' => 'View Orders', 'slug' => 'orders.view'],
            ['name' => 'Process Orders', 'slug' => 'orders.process'],
            ['name' => 'Manage Order Status', 'slug' => 'orders.status.manage'],
        ];

        // =====================================================================
        // 11. PROGRAMS (Order 11)
        // =====================================================================
        $programs = [
            ['name' => 'View Programs', 'slug' => 'programs.view'],
            ['name' => 'Create Programs', 'slug' => 'programs.create'],
            ['name' => 'Edit Programs', 'slug' => 'programs.edit'],
            ['name' => 'Delete Programs', 'slug' => 'programs.delete'],
        ];

        // =====================================================================
        // 12. REPORTS (Order 12)
        // =====================================================================
        $reports = [
            ['name' => 'Generate Reports', 'slug' => 'reports.generate'],
            ['name' => 'Export Reports', 'slug' => 'reports.export'],
        ];

        // =====================================================================
        // 13. SESSIONS (Order 13)
        // =====================================================================
        $sessions = [
            ['name' => 'Start Training Session', 'slug' => 'sessions.start'],
            ['name' => 'End Training Session', 'slug' => 'sessions.end'],
            ['name' => 'Add Session Notes', 'slug' => 'sessions.notes.add'],
        ];

        // =====================================================================
        // 14. STATISTICS (Order 14)
        // =====================================================================
        $statistics = [
            ['name' => 'View Statistics', 'slug' => 'statistics.view'],
            ['name' => 'Create Statistics', 'slug' => 'statistics.create'],
            ['name' => 'Edit Statistics', 'slug' => 'statistics.edit'],
            ['name' => 'Delete Statistics', 'slug' => 'statistics.delete'],
        ];

        // =====================================================================
        // 15. SYSTEM (Order 15)
        // =====================================================================
        $system = [
            ['name' => 'View System Logs', 'slug' => 'system.logs.view'],
            ['name' => 'Manage System Settings', 'slug' => 'system.settings.manage'],
            ['name' => 'Manage Roles & Permissions', 'slug' => 'system.rbac.manage'],
        ];

        // =====================================================================
        // 16. TEAMS (Order 16)
        // =====================================================================
        $teams = [
            ['name' => 'Create Team', 'slug' => 'teams.create'],
            ['name' => 'Edit Team', 'slug' => 'teams.edit'],
            ['name' => 'Assign Players to Team', 'slug' => 'teams.players.assign'],
        ];

        // =====================================================================
        // 17. USERS (Order 17)
        // =====================================================================
        $users = [
            ['name' => 'View Users', 'slug' => 'users.view'],
            ['name' => 'Create Users', 'slug' => 'users.create'],
            ['name' => 'Edit Users', 'slug' => 'users.edit'],
            ['name' => 'Delete Users', 'slug' => 'users.delete'],
            ['name' => 'Approve Users', 'slug' => 'users.approve'],
        ];

        // =====================================================================
        // INSERT ALL PERMISSIONS
        // =====================================================================

        // Helper function to insert permissions with category
        $insertPermissions = function($permissions, $category) {
            foreach ($permissions as $perm) {
                Permission::updateOrCreate(
                    ['slug' => $perm['slug']],
                    [
                        'name' => $perm['name'],
                        'module' => $category,
                        'description' => $perm['name']
                    ]
                );
            }
        };

        // Insert all by category order
        $insertPermissions($matches, 'matches');
        $insertPermissions($documents, 'documents');
        $insertPermissions($jobs, 'jobs');
        $insertPermissions($attendance, 'attendance');
        $insertPermissions($communication, 'communication');
        $insertPermissions($content, 'content');
        $insertPermissions($finance, 'finance');
        $insertPermissions($players, 'players');
        $insertPermissions($partners, 'partners');
        $insertPermissions($orders, 'orders');
        $insertPermissions($programs, 'programs');
        $insertPermissions($reports, 'reports');
        $insertPermissions($sessions, 'sessions');
        $insertPermissions($statistics, 'statistics');
        $insertPermissions($system, 'system');
        $insertPermissions($teams, 'teams');
        $insertPermissions($users, 'users');

        // Log the count
        $this->command->info('Permissions seeded successfully!');
        $this->command->info('Total permissions: ' . Permission::count());
    }
}
