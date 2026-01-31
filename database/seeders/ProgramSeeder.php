<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if table exists and if it already has data
        if (!Schema::hasTable('programs')) {
            $this->command->error('programs table does not exist! Please run migrations first.');
            return;
        }

        $programs = [
            [
                'title' => 'Youth Development Academy',
                'category' => 'Academy',
                'age_group' => 'Under-10',
                'description' => 'Foundational training program for young players aged 10 and under. Focus on fundamental skills, coordination, and love for the game.',
                'schedule' => 'Saturdays, 9:00 AM - 11:00 AM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 30,
                'program_fee' => 5000.00,
                'regular_fee' => 5000.00,
                'mumias_fee' => 2500.00,
                'mumias_discount_percentage' => 50,
                'objectives' => 'Develop fundamental football skills, improve physical coordination, and instill discipline and teamwork.',
                'difficulty_level' => 'Beginner',
                'duration' => '2 hours per session',
                'schedule_details' => json_encode(['day' => 'Saturday', 'time' => '9:00 AM - 11:00 AM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Youth Development Academy',
                'category' => 'Academy',
                'age_group' => 'Under-13',
                'description' => 'Intermediate training program for players aged 11-13. Focus on tactical understanding and technical skills.',
                'schedule' => 'Saturdays, 2:00 PM - 5:00 PM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 25,
                'program_fee' => 7500.00,
                'regular_fee' => 7500.00,
                'mumias_fee' => 3750.00,
                'mumias_discount_percentage' => 50,
                'objectives' => 'Enhance technical abilities, introduce tactical concepts, and prepare players for competitive matches.',
                'difficulty_level' => 'Intermediate',
                'duration' => '3 hours per session',
                'schedule_details' => json_encode(['day' => 'Saturday', 'time' => '2:00 PM - 5:00 PM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Youth Development Academy',
                'category' => 'Academy',
                'age_group' => 'Under-17',
                'description' => 'Advanced training program for players aged 14-17. Focus on advanced tactics, physical conditioning, and competitive play.',
                'schedule' => 'Tuesdays & Thursdays, 4:00 PM - 6:30 PM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 20,
                'program_fee' => 10000.00,
                'regular_fee' => 10000.00,
                'mumias_fee' => 5000.00,
                'mumias_discount_percentage' => 50,
                'objectives' => 'Prepare players for professional or semi-professional football, focusing on advanced skills and match intelligence.',
                'difficulty_level' => 'Advanced',
                'duration' => '2.5 hours per session',
                'schedule_details' => json_encode(['days' => ['Tuesday', 'Thursday'], 'time' => '4:00 PM - 6:30 PM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Goalkeeper Training Program',
                'category' => 'Specialized',
                'age_group' => 'All Ages',
                'description' => 'Specialized goalkeeper training focusing on shot-stopping, positioning, and distribution.',
                'schedule' => 'Wednesdays, 4:00 PM - 6:00 PM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 10,
                'program_fee' => 6000.00,
                'regular_fee' => 6000.00,
                'mumias_fee' => 3000.00,
                'mumias_discount_percentage' => 50,
                'objectives' => 'Develop specialized goalkeeper skills including reflexes, handling, and game leadership.',
                'difficulty_level' => 'All Levels',
                'duration' => '2 hours per session',
                'schedule_details' => json_encode(['day' => 'Wednesday', 'time' => '4:00 PM - 6:00 PM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Adult Fitness & Football',
                'category' => 'Adult',
                'age_group' => 'Adults',
                'description' => 'Fitness-focused football training for adults looking to stay active and play recreationally.',
                'schedule' => 'Sundays, 6:00 AM - 8:00 AM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 25,
                'program_fee' => 4000.00,
                'regular_fee' => 4000.00,
                'mumias_fee' => 2000.00,
                'mumias_discount_percentage' => 50,
                'objectives' => 'Improve fitness levels, maintain football skills, and provide recreational competition.',
                'difficulty_level' => 'Beginner',
                'duration' => '2 hours per session',
                'schedule_details' => json_encode(['day' => 'Sunday', 'time' => '6:00 AM - 8:00 AM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($programs as $program) {
            // Check if program with same title and age_group exists
            $exists = DB::table('programs')
                ->where('title', $program['title'])
                ->where('age_group', $program['age_group'])
                ->exists();

            if (!$exists) {
                DB::table('programs')->insert($program);
                $this->command->info("Created program: {$program['title']} ({$program['age_group']})");
            } else {
                $this->command->info("Program already exists: {$program['title']} ({$program['age_group']})");
            }
        }

        $this->command->info('Programs seeded successfully!');
    }
}
