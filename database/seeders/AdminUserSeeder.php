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
        \App\Models\User::updateOrCreate(
            ['email' => 'shafi@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
            ]
        );

        // Test Player User
        // First create the player record
        $player = \App\Models\Player::updateOrCreate(
            ['email' => 'shafi2@gmail.com'],
            [
                'name' => 'Test Player',
                'first_name' => 'Test',
                'last_name' => 'Player',
                'age' => 18,
                'position' => 'Forward',
                'age_group' => 'U-18',
                'registration_status' => 'Approved',
                'program_id' => 1, // Assuming program ID 1 exists
            ]
        );

        // Then create the user with reference to the player
        \App\Models\User::updateOrCreate(
            ['email' => 'shafi2@gmail.com'],
            [
                'name' => 'Test Player',
                'password' => bcrypt('password'),
                'user_type' => 'player',
                'status' => 'active',
                'player_id' => $player->id,
            ]
        );
    }
}
