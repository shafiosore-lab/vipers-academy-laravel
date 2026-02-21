<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Management
        Permission::updateOrCreate(['slug' => 'view-users'], ['name' => 'View Users', 'module' => 'users']);
        Permission::updateOrCreate(['slug' => 'create-users'], ['name' => 'Create Users', 'module' => 'users']);
        Permission::updateOrCreate(['slug' => 'edit-users'], ['name' => 'Edit Users', 'module' => 'users']);
        Permission::updateOrCreate(['slug' => 'delete-users'], ['name' => 'Delete Users', 'module' => 'users']);
        Permission::updateOrCreate(['slug' => 'approve-users'], ['name' => 'Approve Users', 'module' => 'users']);

        // Player Management
        Permission::create(['name' => 'View Players', 'slug' => 'view-players', 'module' => 'players']);
        Permission::create(['name' => 'Create Players', 'slug' => 'create-players', 'module' => 'players']);
        Permission::create(['name' => 'Edit Players', 'slug' => 'edit-players', 'module' => 'players']);
        Permission::create(['name' => 'Delete Players', 'slug' => 'delete-players', 'module' => 'players']);
        Permission::create(['name' => 'Approve Players', 'slug' => 'approve-players', 'module' => 'players']);

        // Partner Management
        Permission::create(['name' => 'View Partners', 'slug' => 'view-partners', 'module' => 'partners']);
        Permission::create(['name' => 'Create Partners', 'slug' => 'create-partners', 'module' => 'partners']);
        Permission::create(['name' => 'Edit Partners', 'slug' => 'edit-partners', 'module' => 'partners']);
        Permission::create(['name' => 'Delete Partners', 'slug' => 'delete-partners', 'module' => 'partners']);
        Permission::create(['name' => 'Approve Partners', 'slug' => 'approve-partners', 'module' => 'partners']);

        // Content Management
        Permission::create(['name' => 'View Blogs', 'slug' => 'view-blogs', 'module' => 'content']);
        Permission::create(['name' => 'Create Blogs', 'slug' => 'create-blogs', 'module' => 'content']);
        Permission::create(['name' => 'Edit Blogs', 'slug' => 'edit-blogs', 'module' => 'content']);

        Permission::create(['name' => 'View Gallery', 'slug' => 'view-gallery', 'module' => 'content']);
        Permission::create(['name' => 'Create Gallery', 'slug' => 'create-gallery', 'module' => 'content']);
        Permission::create(['name' => 'Edit Gallery', 'slug' => 'edit-gallery', 'module' => 'content']);
        Permission::create(['name' => 'Delete Gallery', 'slug' => 'delete-gallery', 'module' => 'content']);

        // Programs & Training
        Permission::create(['name' => 'View Programs', 'slug' => 'view-programs', 'module' => 'programs']);
        Permission::create(['name' => 'Create Programs', 'slug' => 'create-programs', 'module' => 'programs']);
        Permission::create(['name' => 'Edit Programs', 'slug' => 'edit-programs', 'module' => 'programs']);
        Permission::create(['name' => 'Delete Programs', 'slug' => 'delete-programs', 'module' => 'programs']);

        // Game Statistics
        Permission::create(['name' => 'View Statistics', 'slug' => 'view-statistics', 'module' => 'statistics']);
        Permission::create(['name' => 'Create Statistics', 'slug' => 'create-statistics', 'module' => 'statistics']);
        Permission::create(['name' => 'Edit Statistics', 'slug' => 'edit-statistics', 'module' => 'statistics']);
        Permission::create(['name' => 'Delete Statistics', 'slug' => 'delete-statistics', 'module' => 'statistics']);

        // Matches & Standings
        Permission::create(['name' => 'View Matches', 'slug' => 'view-matches', 'module' => 'matches']);
        Permission::create(['name' => 'Create Matches', 'slug' => 'create-matches', 'module' => 'matches']);
        Permission::create(['name' => 'Edit Matches', 'slug' => 'edit-matches', 'module' => 'matches']);
        Permission::create(['name' => 'Delete Matches', 'slug' => 'delete-matches', 'module' => 'matches']);

        // Financial Management
        Permission::create(['name' => 'View Payments', 'slug' => 'view-payments', 'module' => 'finance']);
        Permission::create(['name' => 'Process Payments', 'slug' => 'process-payments', 'module' => 'finance']);
        Permission::create(['name' => 'View Financial Reports', 'slug' => 'view-financial-reports', 'module' => 'finance']);

        // Orders Management
        Permission::create(['name' => 'View Orders', 'slug' => 'view-orders', 'module' => 'orders']);
        Permission::create(['name' => 'Process Orders', 'slug' => 'process-orders', 'module' => 'orders']);
        Permission::create(['name' => 'Manage Order Status', 'slug' => 'manage-order-status', 'module' => 'orders']);

        // Documents & Compliance
        Permission::create(['name' => 'View Documents', 'slug' => 'view-documents', 'module' => 'documents']);
        Permission::create(['name' => 'Upload Documents', 'slug' => 'upload-documents', 'module' => 'documents']);
        Permission::create(['name' => 'Approve Documents', 'slug' => 'approve-documents', 'module' => 'documents']);

        // Jobs & Careers
        Permission::create(['name' => 'View Jobs', 'slug' => 'view-jobs', 'module' => 'jobs']);
        Permission::create(['name' => 'Create Jobs', 'slug' => 'create-jobs', 'module' => 'jobs']);
        Permission::create(['name' => 'Edit Jobs', 'slug' => 'edit-jobs', 'module' => 'jobs']);
        Permission::create(['name' => 'Delete Jobs', 'slug' => 'delete-jobs', 'module' => 'jobs']);

        // System Administration
        Permission::create(['name' => 'View System Logs', 'slug' => 'view-system-logs', 'module' => 'system']);
        Permission::create(['name' => 'Manage System Settings', 'slug' => 'manage-system-settings', 'module' => 'system']);
        Permission::create(['name' => 'Manage Roles & Permissions', 'slug' => 'manage-roles-permissions', 'module' => 'system']);

        // Partner-specific permissions
        Permission::create(['name' => 'Create Staff Accounts', 'slug' => 'create-staff-accounts', 'module' => 'partners']);
        Permission::create(['name' => 'Manage Staff Roles', 'slug' => 'manage-staff-roles', 'module' => 'partners']);
        Permission::create(['name' => 'View Partner Analytics', 'slug' => 'view-partner-analytics', 'module' => 'partners']);

        // Player-specific permissions
        Permission::create(['name' => 'View Player Portal', 'slug' => 'view-player-portal', 'module' => 'players']);
        Permission::create(['name' => 'Update Player Profile', 'slug' => 'update-player-profile', 'module' => 'players']);
        Permission::create(['name' => 'View Training Data', 'slug' => 'view-training-data', 'module' => 'players']);

        // Session Management (CRITICAL for Assistant Coach)
        Permission::create(['name' => 'Start Training Session', 'slug' => 'start_training_session', 'module' => 'sessions']);
        Permission::create(['name' => 'End Training Session', 'slug' => 'end_training_session', 'module' => 'sessions']);
        Permission::create(['name' => 'Mark Attendance', 'slug' => 'mark_attendance', 'module' => 'attendance']);
        Permission::create(['name' => 'Clock Player In', 'slug' => 'clock_player_in', 'module' => 'attendance']);
        Permission::create(['name' => 'Clock Player Out', 'slug' => 'clock_player_out', 'module' => 'attendance']);
        Permission::create(['name' => 'Add Session Notes', 'slug' => 'add_session_notes', 'module' => 'sessions']);
        Permission::create(['name' => 'View Attendance History', 'slug' => 'view_attendance_history', 'module' => 'attendance']);

        // Team Management
        Permission::create(['name' => 'Create Team', 'slug' => 'create_team', 'module' => 'teams']);
        Permission::create(['name' => 'Edit Team', 'slug' => 'edit_team', 'module' => 'teams']);
        Permission::create(['name' => 'Assign Players to Team', 'slug' => 'assign_players_to_team', 'module' => 'teams']);

        // Communication
        Permission::create(['name' => 'Send Bulk Messages', 'slug' => 'send_bulk_messages', 'module' => 'communication']);
        Permission::create(['name' => 'Send Team Messages', 'slug' => 'send_team_messages', 'module' => 'communication']);
        Permission::create(['name' => 'Approve Announcements', 'slug' => 'approve_announcements', 'module' => 'communication']);

        // Reports
        Permission::create(['name' => 'Generate Reports', 'slug' => 'generate_reports', 'module' => 'reports']);
        Permission::create(['name' => 'Export Reports', 'slug' => 'export_reports', 'module' => 'reports']);

        // System Administration
        Permission::create(['name' => 'Manage Roles & Permissions', 'slug' => 'manage_roles_permissions', 'module' => 'system']);
    }
}
