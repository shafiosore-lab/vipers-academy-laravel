<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - All permissions
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->permissions()->attach($allPermissions->pluck('id'));
        }

        // Marketing Admin
        $marketingAdmin = Role::where('slug', 'marketing-admin')->first();
        if ($marketingAdmin) {
            $permissions = Permission::whereIn('slug', [
                'view-blogs', 'create-blogs', 'edit-blogs',
                'view-gallery', 'create-gallery', 'edit-gallery',
                'view-jobs', 'create-jobs', 'edit-jobs', 'delete-jobs',
            ])->get();
            $marketingAdmin->permissions()->attach($permissions->pluck('id'));
        }

        // Scouting Admin
        $scoutingAdmin = Role::where('slug', 'scouting-admin')->first();
        if ($scoutingAdmin) {
            $permissions = Permission::whereIn('slug', [
                'view-players', 'create-players', 'edit-players',
                'view-statistics', 'create-statistics', 'edit-statistics',
                'view-matches', 'create-matches', 'edit-matches',
            ])->get();
            $scoutingAdmin->permissions()->attach($permissions->pluck('id'));
        }

        // Operations Admin
        $operationsAdmin = Role::where('slug', 'operations-admin')->first();
        if ($operationsAdmin) {
            $permissions = Permission::whereIn('slug', [
                'view-programs', 'create-programs', 'edit-programs',
                'view-users', 'edit-users', 'approve-users',
                'view-documents', 'approve-documents',
                'view-orders', 'process-orders', 'manage-order-status',
            ])->get();
            $operationsAdmin->permissions()->attach($permissions->pluck('id'));
        }

        // Coaching Admin
        $coachingAdmin = Role::where('slug', 'coaching-admin')->first();
        if ($coachingAdmin) {
            $permissions = Permission::whereIn('slug', [
                'view-players', 'edit-players',
                'view-statistics', 'create-statistics', 'edit-statistics',
                'view-programs', 'edit-programs',
                'view-training-data',
            ])->get();
            $coachingAdmin->permissions()->attach($permissions->pluck('id'));
        }

        // Finance Admin
        $financeAdmin = Role::where('slug', 'finance-admin')->first();
        if ($financeAdmin) {
            $permissions = Permission::whereIn('slug', [
                'view-payments', 'process-payments', 'view-financial-reports',
                'view-orders', 'manage-order-status',
            ])->get();
            $financeAdmin->permissions()->attach($permissions->pluck('id'));
        }

        // Partner Staff Roles
        $partnerMarketing = Role::where('slug', 'partner-marketing')->first();
        if ($partnerMarketing) {
            $permissions = Permission::whereIn('slug', [
                'view-blogs', 'create-blogs', 'edit-blogs',
                'view-gallery', 'create-gallery', 'edit-gallery',
                'view-partner-analytics',
            ])->get();
            $partnerMarketing->permissions()->attach($permissions->pluck('id'));
        }

        $partnerScouting = Role::where('slug', 'partner-scouting')->first();
        if ($partnerScouting) {
            $permissions = Permission::whereIn('slug', [
                'view-players', 'create-players', 'edit-players',
                'view-statistics', 'create-statistics',
                'view-partner-analytics',
            ])->get();
            $partnerScouting->permissions()->attach($permissions->pluck('id'));
        }

        $partnerOperations = Role::where('slug', 'partner-operations')->first();
        if ($partnerOperations) {
            $permissions = Permission::whereIn('slug', [
                'view-programs', 'edit-programs',
                'view-users', 'edit-users',
                'view-documents', 'upload-documents',
                'create-staff-accounts', 'manage-staff-roles',
                'view-partner-analytics',
            ])->get();
            $partnerOperations->permissions()->attach($permissions->pluck('id'));
        }

        // 10-Tier Role System Implementation

        // 2. Admin/Operations Manager - High access, no deletion
        $adminOps = Role::where('slug', 'admin-operations')->first();
        if ($adminOps) {
            $permissions = Permission::whereIn('slug', [
                'view-users', 'create-users', 'edit-users', 'approve-users',
                'view-players', 'create-players', 'edit-players', 'approve-players',
                'view-programs', 'create-programs', 'edit-programs',
                'view-documents', 'upload-documents', 'approve-documents',
                'view-orders', 'process-orders', 'manage-order-status',
                'view-system-logs', 'manage-system-settings',
                // No delete permissions
            ])->get();
            $adminOps->permissions()->attach($permissions->pluck('id'));
        }

        // 3. Head Coach - Team-wide management
        $headCoach = Role::where('slug', 'head-coach')->first();
        if ($headCoach) {
            $permissions = Permission::whereIn('slug', [
                'view-players', 'edit-players',
                'view-statistics', 'create-statistics', 'edit-statistics',
                'view-programs', 'create-programs', 'edit-programs',
                'view-matches', 'create-matches', 'edit-matches',
                'start_training_session', 'end_training_session',
                'mark_attendance', 'clock_player_in', 'clock_player_out',
                'add_session_notes', 'view_attendance_history',
                'create_team', 'edit_team', 'assign_players_to_team',
                'view-training-data',
            ])->get();
            $headCoach->permissions()->attach($permissions->pluck('id'));
        }

        // 4. Assistant Coach - Session-based operations (PRIMARY USE CASE)
        $assistantCoach = Role::where('slug', 'assistant-coach')->first();
        if ($assistantCoach) {
            $permissions = Permission::whereIn('slug', [
                'start_training_session', 'end_training_session',
                'mark_attendance', 'clock_player_in', 'clock_player_out',
                'add_session_notes', 'view_attendance_history',
                'view-players', // Read-only for assigned team
                'view-training-data',
                // Cannot edit players, create teams, etc.
            ])->get();
            $assistantCoach->permissions()->attach($permissions->pluck('id'));
        }

        // 5. Team Manager - Logistics & coordination
        $teamManager = Role::where('slug', 'team-manager')->first();
        if ($teamManager) {
            $permissions = Permission::whereIn('slug', [
                'view-players', 'edit-players',
                'view-programs', 'edit-programs',
                'view-orders', 'process-orders',
                'view-documents', 'upload-documents',
                'send_team_messages',
                'generate_reports', 'export_reports',
            ])->get();
            $teamManager->permissions()->attach($permissions->pluck('id'));
        }

        // 6. Safeguarding/Welfare Officer - Welfare-focused
        $safeguarding = Role::where('slug', 'safeguarding-officer')->first();
        if ($safeguarding) {
            $permissions = Permission::whereIn('slug', [
                'view-players', 'edit-players', // For welfare updates
                'view-documents', 'upload-documents', 'approve-documents',
                'view-system-logs', // For monitoring
                'send_bulk_messages', 'approve_announcements',
            ])->get();
            $safeguarding->permissions()->attach($permissions->pluck('id'));
        }

        // 7. Finance Officer - Financial only
        $financeOfficer = Role::where('slug', 'finance-officer')->first();
        if ($financeOfficer) {
            $permissions = Permission::whereIn('slug', [
                'view-payments', 'process-payments', 'view-financial-reports',
                'view-orders', 'manage-order-status',
                'generate_reports', 'export_reports',
            ])->get();
            $financeOfficer->permissions()->attach($permissions->pluck('id'));
        }

        // 8. Media & Communications Officer - Content only
        $mediaOfficer = Role::where('slug', 'media-officer')->first();
        if ($mediaOfficer) {
            $permissions = Permission::whereIn('slug', [
                'view-blogs', 'create-blogs', 'edit-blogs',
                'view-gallery', 'create-gallery', 'edit-gallery',
                'send_bulk_messages', 'send_team_messages', 'approve-announcements',
            ])->get();
            $mediaOfficer->permissions()->attach($permissions->pluck('id'));
        }

        // 9. Parent - Own child only
        $parent = Role::where('slug', 'parent')->first();
        if ($parent) {
            $permissions = Permission::whereIn('slug', [
                'view-player-portal', // Limited to own child
                'view-training-data', // Limited to own child
            ])->get();
            $parent->permissions()->attach($permissions->pluck('id'));
        }

        // 10. Player - Self-view
        $player = Role::where('slug', 'player')->first();
        if ($player) {
            $permissions = Permission::whereIn('slug', [
                'view-player-portal',
                'update-player-profile',
                'view-training-data',
                'upload-documents',
            ])->get();
            $player->permissions()->attach($permissions->pluck('id'));
        }
    }
}
