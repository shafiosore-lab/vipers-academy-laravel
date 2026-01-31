<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 10-Tier Role System Implementation

        // 10-Tier Role System Implementation - Update existing or create new

        // 1. Super Admin - Full system control
        Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full system control with all permissions',
                'type' => 'admin',
                'is_default' => false,
            ]
        );

        // 2. Admin/Operations Manager - High access, no deletion
        Role::updateOrCreate(
            ['slug' => 'admin-operations'],
            [
                'name' => 'Admin/Operations Manager',
                'description' => 'High-level administrative access without deletion capabilities',
                'type' => 'admin',
                'is_default' => false,
            ]
        );

        // 3. Head Coach - Team-wide management
        Role::updateOrCreate(
            ['slug' => 'head-coach'],
            [
                'name' => 'Head Coach',
                'description' => 'Complete coaching management across all teams',
                'type' => 'admin',
                'is_default' => false,
            ]
        );

        // 4. Assistant Coach - Session-based operations (PRIMARY USE CASE)
        Role::updateOrCreate(
            ['slug' => 'assistant-coach'],
            [
                'name' => 'Assistant Coach',
                'description' => 'Session-based operations: start sessions, mark attendance, manage assigned team',
                'type' => 'partner_staff',
                'is_default' => false,
            ]
        );

        // 5. Team Manager - Logistics & coordination
        Role::updateOrCreate(
            ['slug' => 'team-manager'],
            [
                'name' => 'Team Manager',
                'description' => 'Team logistics, coordination, and administrative support',
                'type' => 'partner_staff',
                'is_default' => false,
            ]
        );

        // 6. Safeguarding/Welfare Officer - Welfare-focused
        Role::updateOrCreate(
            ['slug' => 'safeguarding-officer'],
            [
                'name' => 'Safeguarding/Welfare Officer',
                'description' => 'Child protection, welfare monitoring, and safeguarding compliance',
                'type' => 'admin',
                'is_default' => false,
            ]
        );

        // 7. Finance Officer - Financial only
        Role::updateOrCreate(
            ['slug' => 'finance-officer'],
            [
                'name' => 'Finance Officer',
                'description' => 'Financial management, payments, and reporting',
                'type' => 'admin',
                'is_default' => false,
            ]
        );

        // 8. Media & Communications Officer - Content only
        Role::updateOrCreate(
            ['slug' => 'media-officer'],
            [
                'name' => 'Media & Communications Officer',
                'description' => 'Content creation, media management, and communications',
                'type' => 'admin',
                'is_default' => false,
            ]
        );

        // 9. Parent - Own child only
        Role::updateOrCreate(
            ['slug' => 'parent'],
            [
                'name' => 'Parent',
                'description' => 'Access to own child\'s information and academy communications',
                'type' => 'player',
                'is_default' => false,
            ]
        );

        // 10. Player - Self-view (optional for older age groups)
        Role::updateOrCreate(
            ['slug' => 'player'],
            [
                'name' => 'Player',
                'description' => 'Player portal access for training data and academy information',
                'type' => 'player',
                'is_default' => true,
            ]
        );
    }
}
