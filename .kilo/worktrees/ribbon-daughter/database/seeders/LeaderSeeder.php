<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaders = [
            [
                'name' => 'Dr. James Kiprop',
                'role' => 'Academy Director',
                'credentials' => 'PhD Sports Management, UEFA Pro License',
                'bio' => 'Dr. James Kiprop is a highly experienced football administrator with over 20 years in sports management. He leads the academy with a vision to develop the next generation of football talent.',
                'photo_path' => 'leaders/james-kiprop.jpg',
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Collins W',
                'role' => 'Academy Director',
                'credentials' => 'PhD Sports Management',
                'bio' => 'Dr. Collins W brings international experience and expertise to our academy, focusing on holistic player development.',
                'photo_path' => 'leaders/collins-w.jpg',
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Coach Michael Oduya',
                'role' => 'Technical Director',
                'credentials' => 'UEFA A License, Sports Science MSc',
                'bio' => 'Coach Michael Oduya is responsible for the technical direction of all our programs, bringing extensive UEFA coaching experience.',
                'photo_path' => 'leaders/michael-oduya.jpg',
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grace Wanjiku',
                'role' => 'Youth Development Head',
                'credentials' => 'Sports Psych MSc, Safeguarding Lead',
                'bio' => 'Grace Wanjiku leads our youth development programs with a focus on psychological welfare and safe practices.',
                'photo_path' => 'leaders/grace-wanjiku.jpg',
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ahmed Hassan',
                'role' => 'Scouting Director',
                'credentials' => 'Data Analytics BSc, AI Specialist',
                'bio' => 'Ahmed Hassan uses cutting-edge AI and data analytics to identify and recruit promising young talent.',
                'photo_path' => 'leaders/ahmed-hassan.jpg',
                'display_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('leaders')->insert($leaders);
    }
}
