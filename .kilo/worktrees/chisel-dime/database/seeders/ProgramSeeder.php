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
                'title' => 'Computer & Coding Classes',
                'category' => 'Technology & Coding',
                'age_group' => 'U-18',
                'description' => 'Powered by E.N.G.I.N.E USA - Beginner-friendly tech classes where children learn coding, software basics, digital literacy, problem-solving, and digital creativity to bridge the digital divide and prepare them for future tech careers.',
                'schedule' => 'Flexible Schedule',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 50,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Provide free access to comprehensive STEM education, coding basics, digital literacy, innovation, and problem-solving programs powered by our E.N.G.I.N.E USA partnership.',
                'difficulty_level' => 'Beginner to Intermediate',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Flexible Schedule',
                'age_range' => 'Ages 7–18',
                'schedule_details' => json_encode(['day' => 'Flexible', 'time' => 'Flexible workshop scheduling']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Youth Development & Mentorship Program',
                'category' => 'Youth Development & Wellbeing',
                'age_group' => 'U-18',
                'description' => 'Comprehensive youth development program combining intensive holiday camps, life skills development, mentorship sessions, mental wellbeing support, and creation of safe youth development spaces for holistic growth.',
                'schedule' => 'Weekly Sessions & Holiday Intensive Camps',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 100,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Support youth mental health, provide mentorship guidance, create safe spaces for personal development, life skills training, and intensive holiday development camps.',
                'difficulty_level' => 'All Levels',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Weekly & Holiday Camps',
                'age_range' => 'Ages 6–18',
                'schedule_details' => json_encode(['day' => 'Weekly & Holiday periods', 'time' => 'Scheduled sessions & camp hours']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Academic & Exposure Program',
                'category' => 'Education & Career',
                'age_group' => 'U-18',
                'description' => 'Academic guidance, university visits, and career awareness initiatives to support educational advancement.',
                'schedule' => 'Monthly Activities & Quarterly Visits',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 80,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Enhance academic performance, provide career guidance, and offer educational exposure opportunities through university visits and mentorship.',
                'difficulty_level' => 'All Levels',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Monthly & Quarterly',
                'age_range' => 'Ages 12-18',
                'schedule_details' => json_encode(['day' => 'Monthly activities', 'time' => 'Scheduled university visits']),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'Arduino Robotics & Electronics Program',
                'category' => 'STEM & Robotics',
                'age_group' => 'U-18',
                'description' => 'Hands-on Arduino programming and electronics workshop powered by E.N.G.I.N.E USA. Learn to build, code, and innovate with microcontroller technology.',
                'schedule' => 'Weekend Workshops & Project Sessions',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 50,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Introduce youth to electronics, programming, and robotics through Arduino technology. Develop problem-solving skills, creativity, and technical knowledge for future STEM careers.',
                'difficulty_level' => 'Beginner to Advanced',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Weekend Workshops',
                'age_range' => 'Ages 10-18',
                'schedule_details' => json_encode(['day' => 'Weekends', 'time' => 'Workshop sessions & project time']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($programs as $program) {
            // Check if program with same title exists
            $exists = DB::table('programs')
                ->where('title', $program['title'])
                ->exists();

            if (!$exists) {
                DB::table('programs')->insert($program);
                $this->command->info("Created program: {$program['title']}");
            } else {
                // Update existing program
                DB::table('programs')
                    ->where('title', $program['title'])
                    ->update($program);
                $this->command->info("Updated program: {$program['title']}");
            }
        }

        $this->command->info('Programs seeded successfully!');
    }
}
