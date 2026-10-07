<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;

class TestPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $players = [
            // Under-13 Category (2 players)
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'full_name' => 'John Doe',
                'email' => 'john.doe@test.com',
                'phone' => '+254700000001',
                'date_of_birth' => '2013-01-15',
                'category' => 'under-13',
                'position' => 'Forward',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Jane Doe',
                'parent_phone' => '+254700000011',
                'age' => 13,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Smith',
                'full_name' => 'Sarah Smith',
                'email' => 'sarah.smith@test.com',
                'phone' => '+254700000002',
                'date_of_birth' => '2013-03-20',
                'category' => 'under-13',
                'position' => 'Midfielder',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Mike Smith',
                'parent_phone' => '+254700000012',
                'age' => 13,
            ],

            // Under-15 Category (2 players)
            [
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'full_name' => 'David Johnson',
                'email' => 'david.johnson@test.com',
                'phone' => '+254700000003',
                'date_of_birth' => '2011-05-10',
                'category' => 'under-15',
                'position' => 'Defender',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Lisa Johnson',
                'parent_phone' => '+254700000013',
                'age' => 15,
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Brown',
                'full_name' => 'Emma Brown',
                'email' => 'emma.brown@test.com',
                'phone' => '+254700000004',
                'date_of_birth' => '2011-07-25',
                'category' => 'under-15',
                'position' => 'Goalkeeper',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Tom Brown',
                'parent_phone' => '+254700000014',
                'age' => 15,
            ],

            // Under-17 Category (2 players)
            [
                'first_name' => 'Michael',
                'last_name' => 'Wilson',
                'full_name' => 'Michael Wilson',
                'email' => 'michael.wilson@test.com',
                'phone' => '+254700000005',
                'date_of_birth' => '2009-02-14',
                'category' => 'under-17',
                'position' => 'Midfielder',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Anna Wilson',
                'parent_phone' => '+254700000015',
                'age' => 17,
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Davis',
                'full_name' => 'Sophia Davis',
                'email' => 'sophia.davis@test.com',
                'phone' => '+254700000006',
                'date_of_birth' => '2009-09-08',
                'category' => 'under-17',
                'position' => 'Forward',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Robert Davis',
                'parent_phone' => '+254700000016',
                'age' => 17,
            ],

            // Senior Category (1 player)
            [
                'first_name' => 'James',
                'last_name' => 'Anderson',
                'full_name' => 'James Anderson',
                'email' => 'james.anderson@test.com',
                'phone' => '+254700000007',
                'date_of_birth' => '2000-12-03',
                'category' => 'senior',
                'position' => 'Captain',
                'registration_status' => 'Active',
                'parent_guardian_name' => 'Patricia Anderson',
                'parent_phone' => '+254700000017',
                'age' => 25,
            ],
        ];

        foreach ($players as $playerData) {
            Player::create($playerData);
        }

        $this->command->info('Created 7 test players across different categories (U13, U15, U17, Senior)');
    }
}
