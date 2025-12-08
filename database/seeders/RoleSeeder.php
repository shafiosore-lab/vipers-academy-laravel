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
        // Admin roles
        Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Full system access with all permissions',
            'type' => 'admin',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Marketing Admin',
            'slug' => 'marketing-admin',
            'description' => 'Marketing and content management',
            'type' => 'admin',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Scouting Admin',
            'slug' => 'scouting-admin',
            'description' => 'Player scouting and recruitment',
            'type' => 'admin',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Operations Admin',
            'slug' => 'operations-admin',
            'description' => 'Academy operations and logistics',
            'type' => 'admin',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Coaching Admin',
            'slug' => 'coaching-admin',
            'description' => 'Training and coaching management',
            'type' => 'admin',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Finance Admin',
            'slug' => 'finance-admin',
            'description' => 'Financial management and reporting',
            'type' => 'admin',
            'is_default' => false,
        ]);

        // Partner staff roles
        Role::create([
            'name' => 'Partner Marketing',
            'slug' => 'partner-marketing',
            'description' => 'Marketing support for partners',
            'type' => 'partner_staff',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Partner Scouting',
            'slug' => 'partner-scouting',
            'description' => 'Scouting support for partners',
            'type' => 'partner_staff',
            'is_default' => false,
        ]);

        Role::create([
            'name' => 'Partner Operations',
            'slug' => 'partner-operations',
            'description' => 'Operations support for partners',
            'type' => 'partner_staff',
            'is_default' => false,
        ]);

        // Player role (default for all players)
        Role::create([
            'name' => 'Player',
            'slug' => 'player',
            'description' => 'Academy player with access to portal',
            'type' => 'player',
            'is_default' => true,
        ]);
    }
}
