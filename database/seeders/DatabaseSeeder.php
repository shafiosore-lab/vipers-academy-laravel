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
        // Seed roles and permissions first
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Seed all other data
        $this->call([
            ProgramSeeder::class,
            AdminUserSeeder::class,
            FootballMatchSeeder::class,
            PlayerSeeder::class,
            NewsSeeder::class,
            PartnerSeeder::class,
            ProductSeeder::class,
            GallerySeeder::class,
            LeagueStandingsSeeder::class,
            TopScorersSeeder::class,
            CleanSheetsSeeder::class,
            GoalkeeperRankingsSeeder::class,
        ]);
    }
}
