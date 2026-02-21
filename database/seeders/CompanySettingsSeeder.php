<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if settings already exist
        $exists = DB::table('company_settings')->exists();

        if (!$exists) {
            DB::table('company_settings')->insert([
                'company_name' => 'Mumias Vipers Academy',
                'company_email' => 'finance@mumiasvipers.co.ke',
                'company_phone' => '+254 700 123 456',
                'company_address' => 'P.O. Box 1234, Mumias, Kakamega County, Kenya',
                'company_website' => 'https://www.mumiasvipers.co.ke',
                'logo_path' => null,
                'pdf_footer_enabled' => true,
                'pdf_footer_text' => 'Mumias Vipers Academy - Developing Future Stars',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Company settings seeded successfully!');
        } else {
            $this->command->info('Company settings already exist, skipping...');
        }
    }
}
