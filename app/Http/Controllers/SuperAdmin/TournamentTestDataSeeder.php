<?php

/**
 * Tournament Test Data Seeder
 *
 * Run with: php artisan tinker
 * Then: App\Http\Controllers\SuperAdmin\TournamentTestDataSeeder::run()
 */

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentSquad;
use App\Models\Team;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TournamentTestDataSeeder
{
    public static function run($tournamentId = 1)
    {
        echo "Starting tournament test data seeding...\n";

        // Get the tournament
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            echo "Tournament not found with ID: $tournamentId\n";
            return;
        }

        echo "Tournament: {$tournament->name}\n";

        // Clear existing teams and squads for this tournament
        $existingTeams = TournamentTeam::where('tournament_id', $tournamentId)->get();
        foreach ($existingTeams as $team) {
            TournamentSquad::where('tournament_team_id', $team->id)->delete();
        }
        TournamentTeam::where('tournament_id', $tournamentId)->delete();

        echo "Cleared existing tournament teams and squads.\n";

        // Get or create a base team for the organizations
        $organizationId = $tournament->organization_id;

        // Create teams directly (without needing Team model records)
        $teams = self::createTournamentTeams($tournament);

        // Create players for each team
        $players = self::getOrCreatePlayers();

        foreach ($teams as $index => $tournamentTeam) {
            self::addSquadToTeam($tournamentTeam, $players, $index);
        }

        echo "\nTest data seeding completed!\n";
        echo "Created " . count($teams) . " teams with players.\n";

        // Update tournament status to allow viewing
        if ($tournament->status === 'closed') {
            // Keep it closed but ensure it has teams
        }

        return $teams;
    }

    private static function createTournamentTeams($tournament)
    {
        $teamData = [
            [
                'name' => 'Mumias Queens FC',
                'contact_name' => 'John Doe',
                'contact_email' => 'john@mumiasqueens.fc',
                'contact_phone' => '+254700000001',
                'status' => 'approved'
            ],
            [
                'name' => 'Kakamega Bullets',
                'contact_name' => 'Peter Kamau',
                'contact_email' => 'peter@kakamegabullets.co.ke',
                'contact_phone' => '+254700000002',
                'status' => 'approved'
            ],
            [
                'name' => 'Eldoret Falcons',
                'contact_name' => 'Sarah Johnson',
                'contact_email' => 'sarah@eldoretfalcons.co.ke',
                'contact_phone' => '+254700000003',
                'status' => 'approved'
            ],
            [
                'name' => 'Nairobi Strikers',
                'contact_name' => 'David Ochieng',
                'contact_email' => 'david@nairobistrikers.co.ke',
                'contact_phone' => '+254700000004',
                'status' => 'pending'
            ],
            [
                'name' => 'Kisumu United',
                'contact_name' => 'Mary Atieno',
                'contact_email' => 'mary@kisumuunited.co.ke',
                'contact_phone' => '+254700000005',
                'status' => 'approved'
            ],
            [
                'name' => 'Mombasa Stars',
                'contact_name' => 'Ali Hassan',
                'contact_email' => 'ali@mombasastars.co.ke',
                'contact_phone' => '+254700000006',
                'status' => 'approved'
            ],
        ];

        $createdTeams = [];

        foreach ($teamData as $data) {
            $team = TournamentTeam::create([
                'tournament_id' => $tournament->id,
                'team_id' => null, // No linked Team model
                'team_name' => $data['name'],
                'team_contact_name' => $data['contact_name'],
                'team_contact_email' => $data['contact_email'],
                'team_contact_phone' => $data['contact_phone'],
                'approval_status' => $data['status'],
                'registration_date' => now()->subDays(rand(1, 10)),
                'approved_at' => $data['status'] === 'approved' ? now()->subDays(rand(1, 5)) : null,
                'approved_by' => $data['status'] === 'approved' ? 1 : null,
            ]);

            $createdTeams[] = $team;
            echo "Created team: {$data['name']} ({$data['status']})\n";
        }

        return $createdTeams;
    }

    private static function getOrCreatePlayers()
    {
        // Check if there are existing players in the system
        $existingPlayers = Player::limit(100)->get();

        if ($existingPlayers->count() >= 50) {
            echo "Using existing players from database.\n";
            return $existingPlayers;
        }

        // Create more new test players to ensure each team has enough
        $players = [];
        $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Striker'];

        $playerNames = [
            // Goalkeepers
            ['first_name' => 'Emmanuel', 'last_name' => 'Otieno'],
            ['first_name' => 'Joseph', 'last_name' => 'Wanyama'],
            ['first_name' => 'Brian', 'last_name' => 'Oloo'],
            ['first_name' => 'Samson', 'last_name' => 'Owuor'],
            ['first_name' => 'Felix', 'last_name' => 'Ochieng'],
            ['first_name' => 'John', 'last_name' => 'Oduya'],

            // Defenders
            ['first_name' => 'Dennis', 'last_name' => 'Awuondo'],
            ['first_name' => 'Victor', 'last_name' => 'Ochieng'],
            ['first_name' => 'Michael', 'last_name' => 'Onyango'],
            ['first_name' => 'Samuel', 'last_name' => 'Kiplagat'],
            ['first_name' => 'John', 'last_name' => 'Otieno'],
            ['first_name' => 'Peter', 'last_name' => 'Oduor'],
            ['first_name' => 'Francis', 'last_name' => 'Omondi'],
            ['first_name' => 'James', 'last_name' => 'Ochieng'],
            ['first_name' => 'Daniel', 'last_name' => 'Owuor'],
            ['first_name' => 'George', 'last_name' => 'Otieno'],
            ['first_name' => 'Paul', 'last_name' => 'Ouma'],
            ['first_name' => 'Stephen', 'last_name' => 'Opiyo'],
            ['first_name' => 'Charles', 'last_name' => 'Opondo'],
            ['first_name' => 'Alex', 'last_name' => 'Oginga'],

            // Midfielders
            ['first_name' => 'Simon', 'last_name' => 'Otieno'],
            ['first_name' => 'Oscar', 'last_name' => 'Oluoch'],
            ['first_name' => 'Kevin', 'last_name' => 'Ombogo'],
            ['first_name' => 'George', 'last_name' => 'Okoth'],
            ['first_name' => 'Benson', 'last_name' => 'Ochieng'],
            ['first_name' => 'Patrick', 'last_name' => 'Oduya'],
            ['first_name' => 'Timothy', 'last_name' => 'Onyango'],
            ['first_name' => 'Joseph', 'last_name' => 'Oloo'],
            ['first_name' => 'Vincent', 'last_name' => 'Owuor'],
            ['first_name' => 'Edwin', 'last_name' => 'Othieno'],
            ['first_name' => 'Martin', 'last_name' => 'Ongeri'],
            ['first_name' => 'Fred', 'last_name' => 'Odhiambo'],
            ['first_name' => 'Dickens', 'last_name' => 'Ouma'],
            ['first_name' => 'Silas', 'last_name' => 'Ogola'],

            // Strikers
            ['first_name' => 'Nicholas', 'last_name' => 'Otieno'],
            ['first_name' => 'Anthony', 'last_name' => 'Owuor'],
            ['first_name' => 'Vincent', 'last_name' => 'Ochieng'],
            ['first_name' => 'Moses', 'last_name' => 'Onyango'],
            ['first_name' => 'Geoffrey', 'last_name' => 'Omondi'],
            ['first_name' => 'Andrew', 'last_name' => 'Oluoch'],
            ['first_name' => 'Bonface', 'last_name' => 'Ochieng'],
            ['first_name' => 'Clinton', 'last_name' => 'Owuor'],
            ['first_name' => 'Nelson', 'last_name' => 'Ombogo'],
            ['first_name' => 'Evans', 'last_name' => 'Otieno'],
        ];

        foreach ($playerNames as $index => $nameData) {
            $position = $positions[array_rand($positions)];

            $player = Player::create([
                'first_name' => $nameData['first_name'],
                'last_name' => $nameData['last_name'],
                'full_name' => $nameData['first_name'] . ' ' . $nameData['last_name'],
                'age' => rand(18, 35),
                'position' => $position,
                'category' => 'senior',
                'status' => 'active',
            ]);

            $players[] = $player;
        }

        echo "Created " . count($players) . " test players.\n";

        return $players;
    }

    private static function addSquadToTeam($tournamentTeam, $players, $teamIndex)
    {
        $totalPlayers = count($players);
        $totalTeams = 6; // Number of teams in the tournament

        // Calculate players per team (distribute evenly)
        $playersPerTeam = (int) ceil($totalPlayers / $totalTeams);
        $startIndex = $teamIndex * $playersPerTeam;
        $endIndex = min($startIndex + $playersPerTeam, $totalPlayers);

        // Ensure minimum 11 players per team
        if ($endIndex - $startIndex < 11) {
            $startIndex = max(0, $totalPlayers - ($totalTeams * 11));
            $startIndex = $teamIndex * 11;
            $endIndex = min($startIndex + 11, $totalPlayers);
        }

        $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Striker'];

        $jerseyNumbers = range(1, 25);
        shuffle($jerseyNumbers);

        $usedNumbers = [];

        for ($i = $startIndex; $i < $endIndex; $i++) {
            if (!isset($players[$i])) break;

            $player = $players[$i];

            // Assign unique jersey number
            $jerseyNumber = array_pop($jerseyNumbers);
            while (in_array($jerseyNumber, $usedNumbers) && count($jerseyNumbers) > 0) {
                $jerseyNumber = array_pop($jerseyNumbers);
            }
            $usedNumbers[] = $jerseyNumber;

            // Determine position based on player record or random
            $position = $player->position ?? $positions[array_rand($positions)];

            // Random verification status
            $rand = rand(1, 10);
            if ($rand <= 7) {
                $status = 'verified';
                $verifiedAt = now()->subDays(rand(1, 5));
                $verifiedBy = 1;
            } elseif ($rand <= 9) {
                $status = 'pending';
                $verifiedAt = null;
                $verifiedBy = null;
            } else {
                $status = 'verified';
                $verifiedAt = now()->subDays(rand(1, 3));
                $verifiedBy = 1;
            }

            TournamentSquad::create([
                'tournament_team_id' => $tournamentTeam->id,
                'player_id' => $player->id,
                'jersey_number' => $jerseyNumber,
                'position' => $position,
                'verification_status' => $status,
                'verified_at' => $verifiedAt,
                'verified_by' => $verifiedBy,
                'registration_date' => now()->subDays(rand(1, 10)),
                'is_locked' => $tournamentTeam->tournament->status === 'ongoing',
            ]);
        }

        $squadCount = $tournamentTeam->squads()->count();
        echo "  Added $squadCount players to {$tournamentTeam->team_name}\n";
    }
}
