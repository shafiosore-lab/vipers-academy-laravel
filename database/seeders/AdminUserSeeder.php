<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $adminUser = \App\Models\User::updateOrCreate(
            ['email' => 'admin@mumiasvipers.com'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );

        // Assign super-admin role to admin user
        $superAdminRole = \App\Models\Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $adminUser->assignRole($superAdminRole);
        }

        // Test Player User (shafi1@gmail.com)
        $playerUser = \App\Models\User::updateOrCreate(
            ['email' => 'shafi1@gmail.com'],
            [
                'name' => 'Shafi Player',
                'first_name' => 'Shafi',
                'last_name' => 'Player',
                'password' => bcrypt('password'),
                'user_type' => 'player',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );

        // Assign player role
        $playerRole = \App\Models\Role::where('slug', 'player')->first();
        if ($playerRole) {
            $playerUser->assignRole($playerRole);
        }
    }
}
