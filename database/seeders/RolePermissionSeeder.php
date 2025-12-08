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
                'view-news', 'create-news', 'edit-news', 'delete-news',
                'view-gallery', 'create-gallery', 'edit-gallery', 'delete-gallery',
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
                'view-products', 'edit-products',
            ])->get();
            $financeAdmin->permissions()->attach($permissions->pluck('id'));
        }

        // Partner Staff Roles
        $partnerMarketing = Role::where('slug', 'partner-marketing')->first();
        if ($partnerMarketing) {
            $permissions = Permission::whereIn('slug', [
                'view-news', 'create-news', 'edit-news',
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

        // Player Role
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
