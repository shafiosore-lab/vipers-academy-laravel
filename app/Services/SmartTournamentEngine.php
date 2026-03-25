<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use App\Models\TournamentVenue;
use App\Models\TournamentReferee;
use App\Models\TournamentPool;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * SmartTournamentEngine Service
 *
 * Provides advanced tournament features:
 * - Custom Format Builder (drag-and-drop bracket builder)
 * - ELO-Based Team Ranking with dynamic seeding
 * - Hybrid Format Support (group stage + knockout)
 * - Referee Allocation Optimization
 * - Travel Distance Optimization
 */
class SmartTournamentEngine
{
    // ELO Constants
    const ELO_INITIAL_RATING = 1500;
    const ELO_K_FACTOR = 32; // K-factor for ELO calculation
    const ELO_MIN = 100;
    const ELO_MAX = 2800;

    // Travel optimization constants
    const MAX_TRAVEL_DISTANCE_KM = 500; // Maximum preferred travel distance

    /**
     * ============================================================
     * CUSTOM FORMAT BUILDER
     * Drag-and-drop bracket builder for custom tournament structures
     * ============================================================
     */

    /**
     * Create a custom bracket configuration
     */
    public function createCustomBracket(Tournament $tournament, array $config): array
    {
        $bracketConfig = [
            'type' => $config['type'] ?? 'single_elimination',
            'name' => $config['name'] ?? 'Custom Bracket',
            'num_teams' => $config['num_teams'] ?? 0,
            'rounds' => [],
            'structure' => $config['structure'] ?? [],
            'byes' => $config['byes'] ?? 0,
            'created_at' => now()->toIso8601String(),
        ];

        // Generate bracket structure based on type
        $bracketConfig['rounds'] = match($config['type'] ?? 'single_elimination') {
            'single_elimination' => $this->generateSingleEliminationRounds($bracketConfig['num_teams']),
            'double_elimination' => $this->generateDoubleEliminationRounds($bracketConfig['num_teams']),
            'consolation' => $this->generateConsolationRounds($bracketConfig['num_teams']),
            default => $this->generateSingleEliminationRounds($bracketConfig['num_teams']),
        };

        // Save to tournament
        $tournament->update([
            'custom_format_config' => $bracketConfig,
            'custom_format_name' => $bracketConfig['name'],
        ]);

        return $bracketConfig;
    }

    /**
     * Generate single elimination bracket rounds
     */
    protected function generateSingleEliminationRounds(int $numTeams): array
    {
        $rounds = [];
        $bracketSize = $this->nextPowerOf2($numTeams);
        $numRounds = log2($bracketSize);
        $byes = $bracketSize - $numTeams;

        for ($round = 1; $round <= $numRounds; $round++) {
            $matchesInRound = $bracketSize / pow(2, $round);
            $roundMatches = [];

            for ($match = 1; $match <= $matchesInRound; $match++) {
                $roundMatches[] = [
                    'id' => "r{$round}m{$match}",
                    'position' => $match,
                    'teams' => [],
                    'winner_to' => $round < $numRounds ? "r" . ($round + 1) . "m" . ceil($match / 2) : null,
                    'loser_to' => null,
                    'status' => 'pending',
                ];
            }

            $rounds[] = [
                'round' => $round,
                'name' => $round === $numRounds ? 'Final' : ($round === $numRounds - 1 ? 'Semi-Finals' : ($round === $numRounds - 2 ? 'Quarter-Finals' : "Round {$round}")),
                'matches' => $roundMatches,
            ];
        }

        // Add third place match if byes exist
        if ($byes > 0) {
            $rounds[] = [
                'round' => $numRounds + 1,
                'name' => 'Third Place',
                'matches' => [
                    [
                        'id' => 'r' . ($numRounds + 1) . 'm1',
                        'position' => 1,
                        'teams' => [],
                        'winner_to' => null,
                        'loser_to' => null,
                        'status' => 'pending',
                        'is_third_place' => true,
                    ]
                ],
            ];
        }

        return $rounds;
    }

    /**
     * Generate double elimination bracket rounds
     */
    protected function generateDoubleEliminationRounds(int $numTeams): array
    {
        $rounds = [];
        $bracketSize = $this->nextPowerOf2($numTeams);
        $numRounds = log2($bracketSize);

        // Winners bracket
        for ($round = 1; $round <= $numRounds; $round++) {
            $matchesInRound = $bracketSize / pow(2, $round);
            $roundMatches = [];

            for ($match = 1; $match <= $matchesInRound; $match++) {
                $nextRound = $round + 1;
                $nextMatch = ceil($match / 2);

                $roundMatches[] = [
                    'id' => "w{$round}m{$match}",
                    'position' => $match,
                    'bracket' => 'winners',
                    'teams' => [],
                    'winner_to' => "w{$nextRound}m{$nextMatch}",
                    'loser_to' => $round > 1 ? "l" . ($round - 1) . ceil($match / 2) : null,
                    'status' => 'pending',
                ];
            }

            $rounds['winners'][] = [
                'round' => $round,
                'name' => "Winners Round {$round}",
                'matches' => $roundMatches,
            ];
        }

        // Losers bracket (approximately 2x winners rounds)
        $loserRounds = ($numRounds - 1) * 2;
        for ($round = 1; $round <= $loserRounds; $round++) {
            $matchesInRound = max(1, $bracketSize / pow(2, ceil($round / 2 + 1)));
            $roundMatches = [];

            for ($match = 1; $match <= $matchesInRound; $match++) {
                $roundMatches[] = [
                    'id' => "l{$round}m{$match}",
                    'position' => $match,
                    'bracket' => 'losers',
                    'teams' => [],
                    'winner_to' => $round < $loserRounds ? "l" . ($round + 1) . ceil($match / 2) : "gfm1",
                    'loser_to' => null,
                    'status' => 'pending',
                ];
            }

            $rounds['losers'][] = [
                'round' => $round,
                'name' => "Losers Round {$round}",
                'matches' => $roundMatches,
            ];
        }

        // Grand final
        $rounds['final'] = [
            [
                'id' => 'gfm1',
                'position' => 1,
                'bracket' => 'final',
                'teams' => [],
                'winner_to' => null,
                'loser_to' => null,
                'status' => 'pending',
                'is_grand_final' => true,
            ]
        ];

        return $rounds;
    }

    /**
     * Generate consolation bracket rounds
     */
    protected function generateConsolationRounds(int $numTeams): array
    {
        $rounds = [];
        $bracketSize = $this->nextPowerOf2($numTeams);
        $numRounds = log2($bracketSize);

        for ($round = 1; $round <= $numRounds; $round++) {
            $matchesInRound = $bracketSize / pow(2, $round);
            $roundMatches = [];

            for ($match = 1; $match <= $matchesInRound; $match++) {
                $roundMatches[] = [
                    'id' => "c{$round}m{$match}",
                    'position' => $match,
                    'bracket' => 'consolation',
                    'teams' => [],
                    'winner_to' => $round < $numRounds ? "c" . ($round + 1) . ceil($match / 2) : null,
                    'loser_to' => null,
                    'status' => 'pending',
                ];
            }

            $rounds[] = [
                'round' => $round,
                'name' => "Consolation Round {$round}",
                'matches' => $roundMatches,
            ];
        }

        return $rounds;
    }

    /**
     * Update bracket match with teams (drag-and-drop result)
     */
    public function updateBracketMatch(TournamentMatch $match, array $bracketUpdate): TournamentMatch
    {
        $updateData = [
            'custom_bracket_config' => json_encode($bracketUpdate),
            'bracket_position' => $bracketUpdate['position'] ?? null,
        ];

        // Handle progression to next match
        if (isset($bracketUpdate['winner_team_id']) && isset($bracketUpdate['next_match_id'])) {
            $nextMatch = TournamentMatch::find($bracketUpdate['next_match_id']);
            if ($nextMatch) {
                // Determine if home or away based on position
                $position = $bracketUpdate['next_position'] ?? 1;
                $field = $position === 1 ? 'home_team_id' : 'away_team_id';
                $nextMatch->update([$field => $bracketUpdate['winner_team_id']]);
                $match->update(['next_match_id' => $bracketUpdate['next_match_id']]);
            }
        }

        // Handle loser progression for double elimination
        if (isset($bracketUpdate['loser_team_id']) && isset($bracketUpdate['loser_next_match_id'])) {
            $loserMatch = TournamentMatch::find($bracketUpdate['loser_next_match_id']);
            if ($loserMatch) {
                $position = $bracketUpdate['loser_next_position'] ?? 1;
                $field = $position === 1 ? 'home_team_id' : 'away_team_id';
                $loserMatch->update([$field => $bracketUpdate['loser_team_id']]);
                $match->update(['loser_next_match_id' => $bracketUpdate['loser_next_match_id']]);
            }
        }

        return $match->fresh();
    }

    /**
     * ============================================================
     * ELO-BASED TEAM RANKING
     * Dynamic seeding using ELO rating system
     * ============================================================
     */

    /**
     * Calculate ELO rating change after a match
     */
    public function calculateEloChange(int $winnerElo, int $loserElo, bool $isDraw = false): array
    {
        // Expected score for winner
        $expectedWinner = 1 / (1 + pow(10, ($loserElo - $winnerElo) / 400));
        $expectedLoser = 1 - $expectedWinner;

        // Adjust K-factor based on match significance
        $kFactor = self::ELO_K_FACTOR;

        if ($isDraw) {
            $winnerChange = round($kFactor * (0.5 - $expectedWinner));
            $loserChange = round($kFactor * (0.5 - $expectedLoser));
        } else {
            $winnerChange = round($kFactor * (1 - $expectedWinner));
            $loserChange = round($kFactor * (0 - $expectedLoser));
        }

        return [
            'winner_change' => $winnerChange,
            'loser_change' => $loserChange,
        ];
    }

    /**
     * Update ELO ratings after match completion
     */
    public function updateTeamElo(
        TournamentTeam $winner,
        TournamentTeam $loser,
        int $winnerGoals,
        int $loserGoals,
        bool $isDraw = false
    ): array {
        $winnerElo = $winner->elo_rating ?? self::ELO_INITIAL_RATING;
        $loserElo = $loser->elo_rating ?? self::ELO_INITIAL_RATING;

        // Calculate goal difference bonus (minor adjustment)
        $goalDiff = abs($winnerGoals - $loserGoals);
        $goalBonus = min($goalDiff, 3) * 2; // Max 6 point bonus for goal difference

        $changes = $this->calculateEloChange($winnerElo, $loserElo, $isDraw);

        // Apply goal bonus to winner
        $winnerChange = $changes['winner_change'] + ($isDraw ? 0 : $goalBonus);
        $loserChange = $changes['loser_change'];

        // Clamp to min/max
        $winnerChange = max(-50, min(50, $winnerChange));
        $loserChange = max(-50, min(50, $loserChange));

        // Update winner
        $newWinnerElo = max(self::ELO_MIN, min(self::ELO_MAX, $winnerElo + $winnerChange));
        $winner->update([
            'elo_rating' => $newWinnerElo,
            'elo_matches' => ($winner->elo_matches ?? 0) + 1,
            'wins' => ($winner->wins ?? 0) + ($isDraw ? 0 : 1),
            'draws' => ($winner->draws ?? 0) + ($isDraw ? 1 : 0),
            'goals_for' => ($winner->goals_for ?? 0) + $winnerGoals,
            'goals_against' => ($winner->goals_against ?? 0) + $loserGoals,
            'last_elo_change' => $winnerChange,
            'last_match_date' => now(),
        ]);

        // Update loser
        $newLoserElo = max(self::ELO_MIN, min(self::ELO_MAX, $loserElo + $loserChange));
        $loser->update([
            'elo_rating' => $newLoserElo,
            'elo_matches' => ($loser->elo_matches ?? 0) + 1,
            'losses' => ($loser->losses ?? 0) + ($isDraw ? 0 : 1),
            'goals_for' => ($loser->goals_for ?? 0) + $loserGoals,
            'goals_against' => ($loser->goals_against ?? 0) + $winnerGoals,
            'last_elo_change' => $loserChange,
            'last_match_date' => now(),
        ]);

        return [
            'winner' => [
                'team_id' => $winner->id,
                'old_elo' => $winnerElo,
                'new_elo' => $newWinnerElo,
                'change' => $winnerChange,
            ],
            'loser' => [
                'team_id' => $loser->id,
                'old_elo' => $loserElo,
                'new_elo' => $newLoserElo,
                'change' => $loserChange,
            ],
        ];
    }

    /**
     * Seed teams by ELO rating
     */
    public function seedTeamsByElo(Collection $teams): Collection
    {
        return $teams->sortByDesc(function ($team) {
            return $team->elo_rating ?? self::ELO_INITIAL_RATING;
        })->values();
    }

    /**
     * Generate optimized pairings by ELO
     */
    public function generateEloPairings(Collection $teams): array
    {
        $sortedTeams = $this->seedTeamsByElo($teams);
        $teamsArray = $sortedTeams->toArray();
        $numTeams = count($teamsArray);

        $pairings = [];
        for ($i = 0; $i < $numTeams - 1; $i += 2) {
            if (isset($teamsArray[$i + 1])) {
                $pairings[] = [
                    'home' => $teamsArray[$i],
                    'away' => $teamsArray[$i + 1],
                    'elo_diff' => abs(
                        ($teamsArray[$i]->elo_rating ?? self::ELO_INITIAL_RATING) -
                        ($teamsArray[$i + 1]->elo_rating ?? self::ELO_INITIAL_RATING)
                    ),
                ];
            }
        }

        return $pairings;
    }

    /**
     * ============================================================
     * HYBRID FORMAT SUPPORT
     * Group stage + knockout combinations
     * ============================================================
     */

    /**
     * Generate hybrid format (group stage + knockout)
     */
    public function generateHybridFormat(
        Tournament $tournament,
        int $teamsPerGroup = 4,
        int $teamsAdvance = 2,
        bool $includeThirdPlace = false
    ): array {
        $results = [
            'group_stage' => [],
            'knockout_stage' => [],
            'total_matches' => 0,
        ];

        // Update tournament hybrid config
        $tournament->update([
            'hybrid_stage_config' => [
                'teams_per_group' => $teamsPerGroup,
                'teams_advance' => $teamsAdvance,
                'include_third_place' => $includeThirdPlace,
                'generated_at' => now()->toIso8601String(),
            ],
            'teams_from_group' => $teamsAdvance,
            'include_third_place' => $includeThirdPlace,
        ]);

        // Get pools
        $pools = $tournament->pools()->ordered()->get();

        if ($pools->count() === 0) {
            // Auto-create pools if none exist
            $teams = $tournament->approvedTeams()->get();
            $numPools = ceil($teams->count() / $teamsPerGroup);

            for ($i = 1; $i <= $numPools; $i++) {
                $pool = TournamentPool::create([
                    'tournament_id' => $tournament->id,
                    'name' => 'Group ' . chr(64 + $i),
                    'display_order' => $i,
                ]);
                $pools->push($pool);
            }

            // Distribute teams evenly
            $teamsArray = $teams->shuffle()->toArray();
            $teamIndex = 0;
            foreach ($pools as $pool) {
                for ($j = 0; $j < $teamsPerGroup && $teamIndex < count($teamsArray); $j++, $teamIndex++) {
                    $teamsArray[$teamIndex]->update(['pool_id' => $pool->id]);
                }
            }
        }

        // Generate group stage matches
        $matchScheduler = new MatchScheduler();

        foreach ($pools as $pool) {
            $groupMatches = $matchScheduler->generateGroupStageSchedule($tournament, $teamsPerGroup);
            $results['group_stage'] = array_merge($results['group_stage'], $groupMatches);
        }

        $results['total_matches'] += count($results['group_stage']);

        // Generate knockout stage
        $qualifiedTeams = [];
        foreach ($pools as $pool) {
            $poolTeams = $pool->teams()
                ->with('standing')
                ->get()
                ->sortByDesc(function ($t) {
                    return $t->standing?->points ?? 0;
                })
                ->take($teamsAdvance);

            $qualifiedTeams = array_merge($qualifiedTeams, $poolTeams->pluck('id')->toArray());
        }

        // Shuffle qualified teams for knockout draw
        shuffle($qualifiedTeams);

        // Create knockout matches
        $knockoutMatches = $this->generateKnockoutFromQualified($tournament, $qualifiedTeams, $includeThirdPlace);
        $results['knockout_stage'] = $knockoutMatches;
        $results['total_matches'] += count($knockoutMatches);
        $results['qualified_teams'] = $qualifiedTeams;

        return $results;
    }

    /**
     * Generate knockout from qualified teams
     */
    protected function generateKnockoutFromQualified(Tournament $tournament, array $teamIds, bool $includeThirdPlace): array
    {
        $matches = [];
        $numTeams = count($teamIds);

        if ($numTeams < 2) {
            return $matches;
        }

        $bracketSize = $this->nextPowerOf2($numTeams);

        // Pad with byes
        while (count($teamIds) < $bracketSize) {
            $teamIds[] = null;
        }

        // Shuffle for random seeding
        shuffle($teamIds);

        $matchScheduler = new MatchScheduler();
        $knockoutMatches = $matchScheduler->generateKnockoutBracket($tournament, log2($bracketSize));

        // Assign teams to first round matches
        $round1Matches = array_filter($knockoutMatches, fn($m) => $m->round === 1);

        $matchIndex = 0;
        foreach ($round1Matches as $match) {
            if (isset($teamIds[$matchIndex * 2])) {
                $match->update(['home_team_id' => $teamIds[$matchIndex * 2]]);
            }
            if (isset($teamIds[$matchIndex * 2 + 1])) {
                $match->update(['away_team_id' => $teamIds[$matchIndex * 2 + 1]]);
            }
            $matchIndex++;
        }

        return $knockoutMatches;
    }

    /**
     * ============================================================
     * REFEREE ALLOCATION OPTIMIZATION
     * ============================================================
     */

    /**
     * Find optimal referee assignment considering:
     * - Availability
     * - Certification level
     * - Current workload
     * - Rating
     */
    public function suggestRefereeAssignment(
        Tournament $tournament,
        TournamentMatch $match,
        string $role = 'main'
    ): ?TournamentReferee {
        $availableReferees = TournamentReferee::active()
            ->forTournament($tournament->id)
            ->get()
            ->filter(fn($r) => $r->isAvailableAt($match->kickoff_time, $match->match_format['duration'] ?? 90));

        if ($availableReferees->isEmpty()) {
            return null;
        }

        // Score each referee
        $scoredReferees = $availableReferees->map(function ($referee) use ($match, $role) {
            $score = 0;

            // Certification match (prefer higher certification for main referee)
            $certLevels = ['emerging' => 1, 'local' => 2, 'regional' => 3, 'national' => 4, 'international' => 5, 'fifa' => 6];
            $certScore = $certLevels[$referee->certification_level] ?? 1;

            if ($role === 'main') {
                $score += $certScore * 10;
            } else {
                $score += $certScore * 5; // Lower requirement for assistants
            }

            // Rating score (0-30 points based on 5-star rating)
            $score += $referee->rating * 6;

            // Workload balance (prefer less matches)
            $workloadPenalty = $referee->matches_officiated * 2;
            $score -= $workloadPenalty;

            // Experience bonus
            $score += min($referee->matches_officiated / 10, 10);

            return [
                'referee' => $referee,
                'score' => $score,
            ];
        });

        // Return best match
        $best = $scoredReferees->sortByDesc('score')->first();
        return $best ? $best['referee'] : null;
    }

    /**
     * Auto-assign referees to a match
     */
    public function autoAssignReferees(TournamentMatch $match): array
    {
        $assignments = [];

        // Main referee
        $mainReferee = $this->suggestRefereeAssignment($match->tournament, $match, 'main');
        if ($mainReferee) {
            $match->update(['referee_id' => $mainReferee->id]);
            $assignments['main'] = $mainReferee;
        }

        // Assistant referees
        $assistant1 = $this->suggestRefereeAssignment($match->tournament, $match, 'assistant');
        if ($assistant1 && $assistant1->id !== ($mainReferee?->id)) {
            $match->update(['assistant_referee_1_id' => $assistant1->id]);
            $assignments['assistant_1'] = $assistant1;
        }

        $assistant2 = $this->suggestRefereeAssignment($match->tournament, $match, 'assistant');
        if ($assistant2 && $assistant2->id !== ($mainReferee?->id) && $assistant2->id !== ($assistant1?->id)) {
            $match->update(['assistant_referee_2_id' => $assistant2->id]);
            $assignments['assistant_2'] = $assistant2;
        }

        // Fourth official
        $fourthOfficial = $this->suggestRefereeAssignment($match->tournament, $match, 'fourth');
        if ($fourthOfficial && !in_array($fourthOfficial->id, array_column($assignments, 'id'))) {
            $match->update(['fourth_official_id' => $fourthOfficial->id]);
            $assignments['fourth_official'] = $fourthOfficial;
        }

        // Store in JSON as well
        $match->update([
            'match_officials' => [
                'main' => $mainReferee?->id,
                'assistant_1' => $assistant1?->id,
                'assistant_2' => $assistant2?->id,
                'fourth_official' => $fourthOfficial?->id,
                'assigned_at' => now()->toIso8601String(),
            ]
        ]);

        return $assignments;
    }

    /**
     * Check for referee conflicts
     */
    public function checkRefereeConflict(Tournament $tournament, ?int $refereeId, Carbon $dateTime, int $duration = 90): bool
    {
        if (!$refereeId) {
            return false;
        }

        $referee = TournamentReferee::find($refereeId);
        return $referee && !$referee->isAvailableAt($dateTime, $duration);
    }

    /**
     * ============================================================
     * TRAVEL DISTANCE OPTIMIZATION
     * ============================================================
     */

    /**
     * Calculate distance between two venues using coordinates
     */
    public function calculateDistance(TournamentVenue $venue1, TournamentVenue $venue2): ?float
    {
        if (!$venue1->latitude || !$venue1->longitude ||
            !$venue2->latitude || !$venue2->longitude) {
            return null;
        }

        // Haversine formula
        $lat1 = deg2rad($venue1->latitude);
        $lat2 = deg2rad($venue2->latitude);
        $deltaLat = deg2rad($venue2->latitude - $venue1->latitude);
        $deltaLon = deg2rad($venue2->longitude - $venue1->longitude);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = 6371 * $c; // Earth's radius in km

        return round($distance, 2);
    }

    /**
     * Suggest optimal venue for match based on team locations
     */
    public function suggestOptimalVenue(
        Tournament $tournament,
        int $homeTeamId,
        int $awayTeamId,
        Carbon $dateTime
    ): ?TournamentVenue {
        $homeTeam = TournamentTeam::find($homeTeamId);
        $awayTeam = TournamentTeam::find($awayTeamId);

        if (!$homeTeam || !$awayTeam) {
            return null;
        }

        // Get venues with coordinates
        $venues = $tournament->venues()
            ->active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if ($venues->isEmpty()) {
            // Return first available venue if no coordinates
            return $tournament->venues()->active()->first();
        }

        // Find balanced venue (minimize total travel)
        $bestVenue = null;
        $minTotalTravel = PHP_FLOAT_MAX;

        foreach ($venues as $venue) {
            // Check availability
            if (!$venue->isAvailableAt($dateTime)) {
                continue;
            }

            // Calculate travel distances (using venue coordinates)
            // In real scenario, would use team home locations
            $totalTravel = $this->estimateTotalTravel($venue, $venues);

            if ($totalTravel < $minTotalTravel) {
                $minTotalTravel = $totalTravel;
                $bestVenue = $venue;
            }
        }

        return $bestVenue ?? $tournament->venues()->active()->first();
    }

    /**
     * Estimate total travel for all teams to a venue
     */
    protected function estimateTotalTravel(TournamentVenue $targetVenue, Collection $allVenues): float
    {
        $totalDistance = 0;

        // In a real implementation, this would use team home locations
        // For now, estimate based on venue distribution
        foreach ($allVenues as $venue) {
            if ($venue->id !== $targetVenue->id) {
                $distance = $this->calculateDistance($targetVenue, $venue);
                if ($distance) {
                    $totalDistance += $distance;
                }
            }
        }

        return $totalDistance;
    }

    /**
     * Optimize schedule for minimal travel
     */
    public function optimizeScheduleForTravel(Tournament $tournament): array
    {
        $matches = $tournament->matches()
            ->where('status', TournamentMatch::STATUS_SCHEDULED)
            ->whereNotNull('kickoff_time')
            ->orderBy('kickoff_time')
            ->get();

        $optimized = [];
        $usedVenues = [];

        foreach ($matches as $match) {
            if (!$match->home_team_id || !$match->away_team_id) {
                continue;
            }

            // Suggest optimal venue
            $venue = $this->suggestOptimalVenue(
                $tournament,
                $match->home_team_id,
                $match->away_team_id,
                $match->kickoff_time
            );

            if ($venue) {
                // Check if venue is available
                $isAvailable = $venue->isAvailableAt(
                    $match->kickoff_time,
                    $match->match_format['duration'] ?? 90
                );

                if ($isAvailable) {
                    $optimized[] = [
                        'match_id' => $match->id,
                        'current_venue' => $match->venue_id,
                        'suggested_venue' => $venue->id,
                        'venue_name' => $venue->name,
                        'distance_saved' => 'N/A', // Would calculate actual savings
                    ];
                }
            }
        }

        return $optimized;
    }

    /**
     * ============================================================
     * UTILITY METHODS
     * ============================================================
     */

    /**
     * Get next power of 2
     */
    protected function nextPowerOf2(int $n): int
    {
        if ($n <= 0) return 1;
        return pow(2, ceil(log($n, 2)));
    }

    /**
     * Get tournament ELO rankings
     */
    public function getTournamentRankings(Tournament $tournament): Collection
    {
        return $tournament->approvedTeams()
            ->orderByDesc('elo_rating')
            ->orderByDesc('wins')
            ->orderByDesc('goals_for')
            ->get()
            ->map(fn($team, $index) => [
                'rank' => $index + 1,
                'team' => $team,
                'elo_rating' => $team->elo_rating ?? self::ELO_INITIAL_RATING,
                'matches' => $team->elo_matches ?? 0,
                'wins' => $team->wins ?? 0,
                'draws' => $team->draws ?? 0,
                'losses' => $team->losses ?? 0,
                'win_rate' => $team->elo_matches > 0
                    ? round(($team->wins / $team->elo_matches) * 100, 1)
                    : 0,
            ]);
    }
}
