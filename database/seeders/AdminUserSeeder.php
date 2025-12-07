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
                'status' => 'active',
            ]
        );

        // Test Player User (shafi1@gmail.com)
        $playerUser = \App\Models\User::updateOrCreate(
            ['email' => 'shafi1@gmail.com'],
            [
                'name' => 'Shafi Player',
                'password' => bcrypt('password'),
                'user_type' => 'player',
                'status' => 'active',
            ]
        );

        // Create or update associated player record
        \App\Models\Player::updateOrCreate(
            ['id' => 1], // Use a fixed ID for testing
            [
                'name' => 'Shafi Player',
                'first_name' => 'Shafi',
                'last_name' => 'Player',
                'category' => 'senior',
                'position' => 'striker',
                'age' => 20,
                'jersey_number' => '10',
                'bio' => 'Test player for authentication testing',
                'goals' => 15,
                'assists' => 8,
                'appearances' => 25,
                'yellow_cards' => 2,
                'red_cards' => 0,
                'program_id' => 1,
                'approval_type' => 'full',
                'documents_completed' => true,
            ]
        );

        // Link the user to the player
        $playerUser->update(['player_id' => 1]);
    }
}
