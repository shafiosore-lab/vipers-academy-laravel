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
                'title' => 'Sports for Development Program',
                'category' => 'Sports & Leadership',
                'age_group' => 'U-18',
                'description' => 'Football training focused on discipline, teamwork, leadership, and talent identification with pathways to scholarships.',
                'schedule' => 'Weekdays & Weekends, Flexible Sessions',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 150,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Develop athletic talent while fostering discipline, teamwork, leadership qualities, and providing scholarship opportunities.',
                'difficulty_level' => 'All Levels',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Flexible scheduling',
                'age_range' => 'Ages 6-18',
                'schedule_details' => json_encode(['day' => 'Flexible', 'time' => 'Flexible sessions']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'STEM & Digital Skills Program',
                'category' => 'Technology & Innovation',
                'age_group' => 'U-18',
                'description' => 'Powered by E.N.G.I.N.E USA - Coding basics, digital literacy, innovation & problem-solving to bridge the digital divide.',
                'schedule' => 'Weekday Evenings & Weekend Workshops',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 100,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Provide free access to STEM education, digital skills training, and innovation programs powered by our E.N.G.I.N.E USA partnership.',
                'difficulty_level' => 'Beginner to Intermediate',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Evenings & Weekends',
                'age_range' => 'Ages 8-18',
                'schedule_details' => json_encode(['day' => 'Weekday evenings & Weekends', 'time' => 'Flexible workshop scheduling']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Youth Mentorship & Mental Wellbeing Program',
                'category' => 'Personal Development',
                'age_group' => 'U-18',
                'description' => 'Life skills development, mentorship sessions, and creation of safe youth development spaces for holistic growth.',
                'schedule' => 'Weekly Sessions & Workshops',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 120,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Support youth mental health, provide mentorship guidance, and create safe spaces for personal development and life skills training.',
                'difficulty_level' => 'All Levels',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Weekly sessions',
                'age_range' => 'Ages 6-18',
                'schedule_details' => json_encode(['day' => 'Weekly', 'time' => 'Scheduled sessions']),
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
                'title' => 'Girls Empowerment Program',
                'category' => 'Gender Equality & Leadership',
                'age_group' => 'U-18',
                'description' => 'Equal access to sports & STEM opportunities with focus on inclusion and leadership development for girls.',
                'schedule' => 'Dedicated Sessions & Mixed Activities',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'max_participants' => 100,
                'program_fee' => 0.00,
                'regular_fee' => 0.00,
                'mumias_fee' => 0.00,
                'mumias_discount_percentage' => 0,
                'objectives' => 'Promote gender equality in sports and STEM, provide leadership development opportunities, and ensure inclusive access for all girls.',
                'difficulty_level' => 'All Levels',
                'duration' => 'Year-round',
                'fee_display' => 'FREE',
                'schedule_display' => 'Dedicated & Mixed sessions',
                'age_range' => 'Ages 6-18',
                'schedule_details' => json_encode(['day' => 'Flexible scheduling', 'time' => 'Dedicated girls sessions']),
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
