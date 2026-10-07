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
                'title' => 'Weekend Football & Life-Skills Program',
                'category' => 'Football & Life Skills',
                'age_group' => 'U-18',
                'description' => 'A year-round weekend program combining structured football training, academic discipline, digital literacy, character development, and CBC-aligned mentorship.',
                'schedule' => 'Weekends (Saturday & Sunday), 9:00 AM - 1:00 PM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 100,
                'program_fee' => 500.00,
                'regular_fee' => 500.00,
                'mumias_fee' => 500.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Provide consistent development, strong discipline, and a holistic foundation for young athletes.',
                'difficulty_level' => 'All Levels',
                'duration' => 'Full Year',
                'fee_display' => 'KSH 500/month',
                'schedule_display' => 'Full Year',
                'age_range' => 'Ages 6–18',
                'schedule_details' => json_encode(['day' => 'Weekends', 'time' => '9:00 AM - 1:00 PM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Long Holiday Intensive Camp',
                'category' => 'Holiday Intensive Camp',
                'age_group' => 'U-17',
                'description' => 'A fully immersive holiday camp blending football training, academic mentorship, computer exposure, tournaments, teamwork, and life-skills development.',
                'schedule' => 'Daily, 9:00 AM - 3:00 PM',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addWeeks(2)->toDateString(),
                'max_participants' => 50,
                'program_fee' => 5000.00,
                'regular_fee' => 5000.00,
                'mumias_fee' => 5000.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Intensive football training during school holidays with academic mentorship and life-skills development.',
                'difficulty_level' => 'All Levels',
                'duration' => '2 weeks',
                'fee_display' => 'KSH 5,000/holiday',
                'schedule_display' => 'April/Aug/Dec',
                'age_range' => 'Ages 7–17',
                'schedule_details' => json_encode(['day' => 'Daily', 'time' => '9:00 AM - 3:00 PM']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Computer & Coding Classes',
                'category' => 'Technology & Coding',
                'age_group' => 'U-18',
                'description' => 'Beginner-friendly tech classes where children learn coding, software basics, problem-solving, and digital creativity — preparing them for future tech careers.',
                'schedule' => 'Flexible Schedule',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 30,
                'program_fee' => 3500.00,
                'regular_fee' => 3500.00,
                'mumias_fee' => 3500.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Teach coding, software basics, problem-solving, and digital creativity.',
                'difficulty_level' => 'Beginner',
                'duration' => 'Flexible',
                'fee_display' => 'KSH 3,500/month',
                'schedule_display' => 'Flexible Schedule',
                'age_range' => 'Ages 7–18',
                'schedule_details' => json_encode(['day' => 'Flexible', 'time' => 'Flexible']),
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
