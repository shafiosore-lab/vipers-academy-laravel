<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder simply ensures the players folder exists.
     * Players are automatically synced from images in public/assets/img/players/
     */
    public function run(): void
    {
        // Ensure the players directory exists
        $playersPath = public_path('assets/img/players');

        if (!File::exists($playersPath)) {
            File::makeDirectory($playersPath, 0755, true);
            $this->command->info('✓ Created directory: public/assets/img/players/');
        } else {
            $this->command->info('✓ Directory already exists: public/assets/img/players/');
        }

        // Check if there are any images in the folder
        $files = File::files($playersPath);
        $imageFiles = collect($files)->filter(function($file) {
            $extension = strtolower($file->getExtension());
            return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        if ($imageFiles->count() > 0) {
            $this->command->info("✓ Found {$imageFiles->count()} player image(s) in the folder.");
            $this->command->info('  Players will be automatically synced when you visit /players');
        } else {
            $this->command->warn('⚠ No player images found in public/assets/img/players/');
            $this->command->info('');
            $this->command->info('To add players, upload images with this naming format:');
            $this->command->info('  firstname-lastname-category-position-age.jpg');
            $this->command->info('');
            $this->command->info('Examples:');
            $this->command->info('  • john-smith-senior-striker-18.jpg');
            $this->command->info('  • sarah-jones-under-15-goalkeeper-14.jpg');
            $this->command->info('  • mike-ochieng-under-17-midfielder-16.jpg');
            $this->command->info('  • grace-wanjiku-under-13-defender-11.jpg');
            $this->command->info('');
            $this->command->info('Categories: under-13, under-15, under-17, senior');
            $this->command->info('Positions: goalkeeper, defender, midfielder, striker');
            $this->command->info('');
            $this->command->info('After adding images, visit: http://127.0.0.1:8000/players');
        }

        $this->command->info('');
        $this->command->info('════════════════════════════════════════════════');
        $this->command->info('  Players will auto-sync from the images folder');
        $this->command->info('  No manual database entries needed!');
        $this->command->info('════════════════════════════════════════════════');
    }
}
