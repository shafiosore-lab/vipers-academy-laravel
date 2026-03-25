<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Delete all existing users
        User::truncate();

        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'superadmin@example.com',
            'phone' => '+254700000000',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $superAdmin->assignRole('super-admin');

        // Create Organization Admin
        $orgAdmin = User::create([
            'name' => 'Organization Admin',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'orgadmin@example.com',
            'phone' => '+254711111111',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $orgAdmin->assignRole('org-admin');

        // Create Operations Admin
        $opsAdmin = User::create([
            'name' => 'Operations Admin',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'opsadmin@example.com',
            'phone' => '+254722222222',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $opsAdmin->assignRole('admin-operations');

        // Create Finance Admin
        $financeAdmin = User::create([
            'name' => 'Finance Admin',
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'email' => 'financeadmin@example.com',
            'phone' => '+254733333333',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $financeAdmin->assignRole('finance-officer');

        // Create Head Coach
        $headCoach = User::create([
            'name' => 'Head Coach',
            'first_name' => 'Michael',
            'last_name' => 'Wilson',
            'email' => 'headcoach@example.com',
            'phone' => '+254755555555',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $headCoach->assignRole('head-coach');

        // Create Coach
        $coach = User::create([
            'name' => 'Coach',
            'first_name' => 'Sarah',
            'last_name' => 'Davis',
            'email' => 'coach@example.com',
            'phone' => '+254766666666',
            'password' => Hash::make('password123'),
            'user_type' => 'partner_staff',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $coach->assignRole('coach');

        // Create Assistant Coach
        $assistantCoach = User::create([
            'name' => 'Assistant Coach',
            'first_name' => 'Tom',
            'last_name' => 'Anderson',
            'email' => 'assistantcoach@example.com',
            'phone' => '+254777777777',
            'password' => Hash::make('password123'),
            'user_type' => 'partner_staff',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $assistantCoach->assignRole('assistant-coach');

        // Create Team Manager
        $teamManager = User::create([
            'name' => 'Team Manager',
            'first_name' => 'Lisa',
            'last_name' => 'Taylor',
            'email' => 'teammanager@example.com',
            'phone' => '+254788888888',
            'password' => Hash::make('password123'),
            'user_type' => 'partner_staff',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $teamManager->assignRole('team-manager');

        // Create Media Officer
        $mediaOfficer = User::create([
            'name' => 'Media Officer',
            'first_name' => 'Chris',
            'last_name' => 'Martinez',
            'email' => 'mediaofficer@example.com',
            'phone' => '+254799999999',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $mediaOfficer->assignRole('media-officer');

        // Create Safeguarding Officer
        $safeguardingOfficer = User::create([
            'name' => 'Safeguarding Officer',
            'first_name' => 'Emma',
            'last_name' => 'Garcia',
            'email' => 'safeguarding@example.com',
            'phone' => '+254712345678',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $safeguardingOfficer->assignRole('safeguarding-officer');

        // Create Player
        $player = User::create([
            'name' => 'Player One',
            'first_name' => 'Alex',
            'last_name' => 'Johnson',
            'email' => 'player@example.com',
            'phone' => '+254723456789',
            'password' => Hash::make('password123'),
            'user_type' => 'player',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $player->assignRole('player');

        // Create Parent
        $parent = User::create([
            'name' => 'Parent One',
            'first_name' => 'Maria',
            'last_name' => 'Wilson',
            'email' => 'parent@example.com',
            'phone' => '+254734567890',
            'password' => Hash::make('password123'),
            'user_type' => 'player',
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $parent->assignRole('parent');

        $this->command->info('Test users created successfully.');
        $this->command->info('Super Admin: superadmin@example.com / password123');
        $this->command->info('Org Admin: orgadmin@example.com / password123');
        $this->command->info('Finance Admin: financeadmin@example.com / password123');
        $this->command->info('Head Coach: headcoach@example.com / password123');
        $this->command->info('Coach: coach@example.com / password123');
        $this->command->info('Player: player@example.com / password123');
    }
}
