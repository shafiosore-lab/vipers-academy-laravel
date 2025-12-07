<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Program::insert([
            'id' => 1,
            'title' => 'Default Program',
            'description' => 'Default program for testing',
            'age_group' => 'U-18',
            'schedule' => 'Weekly sessions',
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(6)->toDateString(),
            'program_fee' => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
