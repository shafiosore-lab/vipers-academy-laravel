<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebsitePlayer;

class PopulateGenderForExistingPlayers extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder populates the gender field for existing players.
     * Default logic: Set to 'M' (Male) for most players, but you can customize this.
     */
    public function run(): void
    {
        $this->command->info('Populating gender data for existing players...');

        // Get all players without gender set
        $playersWithoutGender = WebsitePlayer::whereNull('gender')->get();

        if ($playersWithoutGender->isEmpty()) {
            $this->command->info('No players found without gender data.');
            return;
        }

        $this->command->info("Found {$playersWithoutGender->count()} players without gender data.");

        // Default gender mapping based on category or other criteria
        foreach ($playersWithoutGender as $player) {
            $gender = $this->determineGender($player);
            $player->update(['gender' => $gender]);
        }

        $this->command->info('Gender data populated successfully for existing players.');
    }

    /**
     * Determine the appropriate gender for a player.
     * Customize this logic based on your specific requirements.
     */
    private function determineGender(WebsitePlayer $player): string
    {
        // Example logic - you can customize this based on your data
        // For now, defaulting to Men, but you might want to:
        // - Check category patterns
        // - Look for specific naming patterns
        // - Use external data sources

        $category = strtolower($player->category ?? '');

        // Example: If category contains 'girls' or 'women' or 'female', set to Women
        if (strpos($category, 'girls') !== false ||
            strpos($category, 'women') !== false ||
            strpos($category, 'female') !== false) {
            return 'F';
        }

        // Default to Men for all others
        // IMPORTANT: Review this default and adjust based on your actual data
        return 'M';
    }
}
