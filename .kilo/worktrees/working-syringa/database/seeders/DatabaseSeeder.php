<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed authentication (roles, permissions, and test users) - consolidated
        $this->call([
            AuthenticationSeeder::class,
        ]);

        // Seed all other data
        $this->call([
            ProgramSeeder::class,
            AdminUserSeeder::class,
            FootballMatchSeeder::class,
            PlayerSeeder::class,
            BlogSeeder::class,
//            PartnerSeeder::class,
            LeagueStandingsSeeder::class,
            TopScorersSeeder::class,
            CleanSheetsSeeder::class,
            GoalkeeperRankingsSeeder::class,
            PaymentCategorySeeder::class,
            FootballTerminologySeeder::class,
            PageContentSeeder::class,
        ]);
    }
}
