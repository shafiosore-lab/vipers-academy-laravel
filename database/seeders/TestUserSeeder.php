<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean all existing users
        User::query()->delete();

        // 1. Full system access admin
        $fullAdmin = User::create([
            'name' => 'Admin User',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@mumiasvipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $fullAdmin->assignRole($superAdminRole);
        }

        // 2. Sub admin
        $subAdmin = User::create([
            'name' => 'Washiali Admin',
            'first_name' => 'Washiali',
            'last_name' => 'Admin',
            'email' => 'washiali@vipers.com',
            'password' => bcrypt('passwords'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        // Assign all admin roles except super-admin
        $adminRoles = Role::where('type', 'admin')->where('slug', '!=', 'super-admin')->get();
        foreach ($adminRoles as $role) {
            $subAdmin->assignRole($role);
        }

        // 3. Partner
        $partner = User::create([
            'name' => 'Partner User',
            'first_name' => 'Partner',
            'last_name' => 'User',
            'email' => 'partner@vipers.com',
            'password' => bcrypt('passwords'),
            'user_type' => 'partner',
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        // Partners might not need specific roles, or assign partner roles if needed
        // For now, no role assigned

        // 4. Player
        $player = User::create([
            'name' => 'Player User',
            'first_name' => 'Player',
            'last_name' => 'User',
            'email' => 'player@vipers.com',
            'password' => bcrypt('passwords'),
            'user_type' => 'player',
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        $playerRole = Role::where('slug', 'player')->first();
        if ($playerRole) {
            $player->assignRole($playerRole);
        }
    }
}
