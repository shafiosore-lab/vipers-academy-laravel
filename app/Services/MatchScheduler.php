<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentPool;
use App\Models\TournamentTeam;
use App\Models\TournamentVenue;
use App\Services\SmartTournamentEngine;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * MatchScheduler Service
 *
 * Provides intelligent match scheduling with:
 * - Conflict detection for venues and teams
 * - Auto-pairing optimization based on rankings
 * - Batch bracket/schedule generation
 * - Manual override capabilities
 */
class MatchScheduler
{
    /**
     * Time slots configuration (24-hour format)
     */
    protected array $timeSlots = [
        '08:00', '09:00', '10:00', '11:00', '12:00',
        '13:00', '14:00', '15:00', '16:00', '17:00',
        '18:00', '19:00', '20:00'
    ];

    /**
     * Default match duration in minutes
     */
    protected int $defaultMatchDuration = 90;

    /**
     * Check for venue conflicts at given time
     */
    public function checkVenueConflict(Tournament $tournament, ?int $venueId, Carbon $dateTime, int $duration = 90, ?int $excludeMatchId = null): bool
    {
        if (!$venueId) {
            return false;
        }

        $endTime = $dateTime->copy()->addMinutes($duration);

        $query = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('venue_id', $venueId)
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->where(function ($q) use ($dateTime, $endTime) {
                $q->where('kickoff_time', '<', $endTime)
                  ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            });

        if ($excludeMatchId) {
            $query->where('id', '!=', $excludeMatchId);
        }

        return $query->exists();
    }

    /**
     * Check for team conflicts at given time
     */
    public function checkTeamConflict(Tournament $tournament, ?int $homeTeamId, ?int $awayTeamId, Carbon $dateTime, int $duration = 90, ?int $excludeMatchId = null): bool
    {
        if (!$homeTeamId || !$awayTeamId) {
            return false;
        }

        $endTime = $dateTime->copy()->addMinutes($duration);

        $query = TournamentMatch::where('tournament_id', $tournament->id)
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->where(function ($q) use ($dateTime, $endTime) {
                $q->where('kickoff_time', '<', $endTime)
                  ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            })
            ->where(function ($q) use ($homeTeamId, $awayTeamId) {
                $q->where('home_team_id', $homeTeamId)
                  ->orWhere('home_team_id', $awayTeamId)
                  ->orWhere('away_team_id', $homeTeamId)
                  ->orWhere('away_team_id', $awayTeamId);
            });

        if ($excludeMatchId) {
            $query->where('id', '!=', $excludeMatchId);
        }

        return $query->exists();
    }

    /**
     * Find next available time slot for a venue
     */
    public function findAvailableSlot(Tournament $tournament, ?int $venueId, Carbon $startDate, int $duration = 90): ?Carbon
    {
        $currentDate = $startDate->copy();
        $maxDaysAhead = 30; // Look ahead 30 days

        for ($day = 0; $day < $maxDaysAhead; $day++) {
            foreach ($this->timeSlots as $time) {
                $slotTime = $currentDate->copy()->setTimeFromTimeString($time);

                // Skip past times
                if ($slotTime->isPast()) {
                    continue;
                }

                if (!$this->checkVenueConflict($tournament, $venueId, $slotTime, $duration)) {
                    return $slotTime;
                }
            }

            $currentDate->addDay();
        }

        return null;
    }

    /**
     * Get all conflicts for a given match
     */
    public function getMatchConflicts(TournamentMatch $match): array
    {
        $conflicts = [];

        $format = $match->match_format ?? TournamentMatch::getDefaultFormat($match->match_type);
        $duration = $format['duration'] ?? $this->defaultMatchDuration;

        if ($match->venue_id && $match->kickoff_time) {
            if ($this->checkVenueConflict($match->tournament, $match->venue_id, $match->kickoff_time, $duration, $match->id)) {
                $conflicts[] = [
                    'type' => 'venue',
                    'message' => 'Venue is already booked at this time',
                ];
            }
        }

        if ($match->home_team_id && $match->away_team_id && $match->kickoff_time) {
            if ($this->checkTeamConflict($match->tournament, $match->home_team_id, $match->away_team_id, $match->kickoff_time, $duration, $match->id)) {
                $conflicts[] = [
                    'type' => 'team',
                    'message' => 'One or both teams are playing at this time',
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Generate optimized pairings based on team rankings
     */
    public function generateOptimizedPairings(Collection $teams, string $method = 'ranking'): array
    {
        $teamsArray = $teams->toArray();
        $numTeams = count($teamsArray);

        if ($numTeams < 2) {
            return [];
        }

        return match ($method) {
            'ranking' => $this->generateByRanking($teamsArray),
            'random' => $this->generateRandom($teamsArray),
            'seeding' => $this->generateBySeeding($teamsArray),
            'performance' => $this->generateByPerformance($teamsArray),
            default => $this->generateByRanking($teamsArray),
        };
    }

    /**
     * Generate pairings by ranking (1v2, 3v4, etc.)
     */
    protected function generateByRanking(array $teams): array
    {
        usort($teams, function ($a, $b) {
            $rankA = $a->seed_number ?? 999;
            $rankB = $b->seed_number ?? 999;
            return $rankA - $rankB;
        });

        $pairings = [];
        $numTeams = count($teams);

        for ($i = 0; $i < $numTeams - 1; $i += 2) {
            if (isset($teams[$i + 1])) {
                $pairings[] = [
                    'home' => $teams[$i],
                    'away' => $teams[$i + 1],
                ];
            }
        }

        return $pairings;
    }

    /**
     * Generate random pairings
     */
    protected function generateRandom(array $teams): array
    {
        shuffle($teams);
        return $this->generateByRanking($teams);
    }

    /**
     * Generate pairings by seed number
     */
    protected function generateBySeeding(array $teams): array
    {
        return $this->generateByRanking($teams);
    }

    /**
     * Generate pairings by previous performance
     */
    protected function generateByPerformance(array $teams): array
    {
        usort($teams, function ($a, $b) {
            $pointsA = $a->standing?->points ?? 0;
            $pointsB = $b->standing?->points ?? 0;
            return $pointsB - $pointsA;
        });

        return $this->generateByRanking($teams);
    }

    /**
     * Schedule matches with conflict avoidance
     */
    public function scheduleMatches(Tournament $tournament, array $matches, Carbon $startDate, ?int $venueId = null): array
    {
        $scheduled = [];
        $currentDate = $startDate->copy();

        foreach ($matches as $matchData) {
            $homeTeamId = $matchData['home_team_id'] ?? null;
            $awayTeamId = $matchData['away_team_id'] ?? null;
            $duration = $matchData['duration'] ?? $this->defaultMatchDuration;
            $matchVenueId = $matchData['venue_id'] ?? $venueId;

            // Find available slot
            $slot = $this->findAvailableSlot($tournament, $matchVenueId, $currentDate, $duration);

            if (!$slot) {
                Log::warning("Could not find available slot for match", $matchData);
                continue;
            }

            $match = TournamentMatch::create([
                'tournament_id' => $tournament->id,
                'home_team_id' => $homeTeamId,
                'away_team_id' => $awayTeamId,
                'venue_id' => $matchVenueId,
                'venue' => $matchVenueId ? TournamentVenue::find($matchVenueId)?->name : null,
                'kickoff_time' => $slot,
                'timezone' => config('app.timezone', 'UTC'),
                'match_type' => $matchData['match_type'] ?? TournamentMatch::TYPE_LEAGUE,
                'pool_id' => $matchData['pool_id'] ?? null,
                'round' => $matchData['round'] ?? 1,
                'match_day' => $matchData['match_day'] ?? 1,
                'status' => TournamentMatch::STATUS_SCHEDULED,
                'match_format' => $matchData['match_format'] ?? TournamentMatch::getDefaultFormat($matchData['match_type'] ?? TournamentMatch::TYPE_LEAGUE),
                'scoring_rules' => TournamentMatch::getDefaultScoringRules(),
            ]);

            $scheduled[] = $match;

            // Move to next time slot
            $currentDate = $slot->copy()->addMinutes($duration + 15); // 15 min buffer
        }

        return $scheduled;
    }

    /**
     * Generate full league schedule (round-robin)
     */
    public function generateLeagueSchedule(Tournament $tournament, ?int $venueId = null): array
    {
        $teams = $tournament->approvedTeams()->get();

        if ($teams->count() < 2) {
            return [];
        }

        $teamList = $teams->pluck('id')->toArray();
        $numTeams = count($teamList);
        $numRounds = $numTeams - 1;

        // Check if odd number of teams
        if ($numTeams % 2 !== 0) {
            $teamList[] = null; // Bye week
            $numTeams++;
        }

        $matchesPerRound = $numTeams / 2;
        $scheduledMatches = [];

        $startDate = $tournament->start_date ?? Carbon::now()->addWeek();
        $currentDate = Carbon::parse($startDate);

        for ($round = 0; $round < $numRounds; $round++) {
            for ($i = 0; $i < $matchesPerRound; $i++) {
                $homeIndex = $i;
                $awayIndex = $numTeams - 1 - $i;

                $homeTeamId = $teamList[$homeIndex] ?? null;
                $awayTeamId = $teamList[$awayIndex] ?? null;

                // Skip byes
                if (!$homeTeamId || !$awayTeamId) {
                    continue;
                }

                $matchVenue = $venueId;

                // Find available slot
                $slot = $this->findAvailableSlot($tournament, $matchVenue, $currentDate);

                if ($slot) {
                    $match = TournamentMatch::create([
                        'tournament_id' => $tournament->id,
                        'home_team_id' => $homeTeamId,
                        'away_team_id' => $awayTeamId,
                        'venue_id' => $venueId,
                        'kickoff_time' => $slot,
                        'timezone' => config('app.timezone', 'UTC'),
                        'match_type' => TournamentMatch::TYPE_LEAGUE,
                        'round' => $round + 1,
                        'match_day' => $round + 1,
                        'status' => TournamentMatch::STATUS_SCHEDULED,
                        'match_format' => TournamentMatch::getDefaultFormat(TournamentMatch::TYPE_LEAGUE),
                        'scoring_rules' => TournamentMatch::getDefaultScoringRules(),
                    ]);

                    $scheduledMatches[] = $match;

                    $currentDate = $slot->copy()->addMinutes(105); // 90 + 15 buffer
                }
            }

            // Rotate teams
            if ($numTeams > 2) {
                $lastTeam = array_pop($teamList);
                array_splice($teamList, 1, 0, $lastTeam);
            }

            // Move to next day
            $currentDate->addDay()->setTime(9, 0);
        }

        return $scheduledMatches;
    }

    /**
     * Generate knockout bracket
     */
    public function generateKnockoutBracket(Tournament $tournament, int $rounds = null, ?int $venueId = null): array
    {
        $teams = $tournament->approvedTeams()->get();

        if ($teams->count() < 2) {
            return [];
        }

        $numTeams = $teams->count();
        $rounds = $rounds ?? (int)ceil(log2($numTeams));
        $bracketSize = pow(2, $rounds);

        // Pad teams with byes
        $teamList = $teams->pluck('id')->toArray();
        while (count($teamList) < $bracketSize) {
            $teamList[] = null;
        }

        // Shuffle for random seeding
        shuffle($teamList);

        $matches = [];
        $startDate = $tournament->start_date ?? Carbon::now()->addWeek();
        $currentDate = Carbon::parse($startDate);

        // First round
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $homeTeam = $teamList[$i];
            $awayTeam = $teamList[$i + 1];

            // Bye handling
            if ($homeTeam === null || $awayTeam === null) {
                continue;
            }

            $slot = $this->findAvailableSlot($tournament, $venueId, $currentDate);

            if ($slot) {
                $match = TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'home_team_id' => $homeTeam,
                    'away_team_id' => $awayTeam,
                    'venue_id' => $venueId,
                    'kickoff_time' => $slot,
                    'timezone' => config('app.timezone', 'UTC'),
                    'match_type' => TournamentMatch::TYPE_KNOCKOUT,
                    'round' => 1,
                    'status' => TournamentMatch::STATUS_SCHEDULED,
                    'match_format' => TournamentMatch::getDefaultFormat(TournamentMatch::TYPE_KNOCKOUT),
                    'scoring_rules' => TournamentMatch::getDefaultScoringRules(),
                ]);

                $matches[] = $match;
                $currentDate = $slot->copy()->addMinutes(105);
            }
        }

        // Generate subsequent rounds (placeholders)
        for ($round = 2; $round <= $rounds; $round++) {
            $numMatches = $bracketSize / pow(2, $round);

            for ($i = 0; $i < $numMatches; $i++) {
                $match = TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'home_team_id' => null,
                    'away_team_id' => null,
                    'venue_id' => $venueId,
                    'match_type' => TournamentMatch::TYPE_KNOCKOUT,
                    'round' => $round,
                    'status' => TournamentMatch::STATUS_SCHEDULED,
                    'match_format' => TournamentMatch::getDefaultFormat(TournamentMatch::TYPE_KNOCKOUT),
                ]);

                $matches[] = $match;
            }
        }

        return $matches;
    }

    /**
     * Generate group stage schedule
     */
    public function generateGroupStageSchedule(Tournament $tournament, int $teamsPerGroup = 4, ?int $venueId = null): array
    {
        $pools = $tournament->pools()->ordered()->get();

        if ($pools->count() === 0) {
            return [];
        }

        $matches = [];
        $startDate = $tournament->start_date ?? Carbon::now()->addWeek();
        $currentDate = Carbon::parse($startDate);

        foreach ($pools as $pool) {
            $teams = $pool->teams()->get();

            if ($teams->count() < 2) {
                continue;
            }

            $teamList = $teams->pluck('id')->toArray();
            $numTeams = count($teamList);
            $numRounds = $numTeams - 1;
            $matchesPerRound = (int)($numTeams / 2);

            for ($round = 1; $round <= $numRounds; $round++) {
                for ($i = 0; $i < $matchesPerRound; $i++) {
                    $homeIndex = $i;
                    $awayIndex = $numTeams - 1 - $i;

                    $homeTeamId = $teamList[$homeIndex] ?? null;
                    $awayTeamId = $teamList[$awayIndex] ?? null;

                    if (!$homeTeamId || !$awayTeamId) {
                        continue;
                    }

                    $slot = $this->findAvailableSlot($tournament, $venueId, $currentDate);

                    if ($slot) {
                        $match = TournamentMatch::create([
                            'tournament_id' => $tournament->id,
                            'home_team_id' => $homeTeamId,
                            'away_team_id' => $awayTeamId,
                            'pool_id' => $pool->id,
                            'venue_id' => $venueId,
                            'kickoff_time' => $slot,
                            'timezone' => config('app.timezone', 'UTC'),
                            'match_type' => TournamentMatch::TYPE_GROUP_STAGE,
                            'round' => $round,
                            'match_day' => $round,
                            'status' => TournamentMatch::STATUS_SCHEDULED,
                            'match_format' => TournamentMatch::getDefaultFormat(TournamentMatch::TYPE_GROUP_STAGE),
                            'scoring_rules' => TournamentMatch::getDefaultScoringRules(),
                        ]);

                        $matches[] = $match;
                        $currentDate = $slot->copy()->addMinutes(105);
                    }
                }

                // Rotate teams
                if ($numTeams > 2) {
                    $lastTeam = array_pop($teamList);
                    array_splice($teamList, 1, 0, $lastTeam);
                }
            }
        }

        return $matches;
    }

    /**
     * Get schedule conflicts summary
     */
    public function getScheduleConflicts(Tournament $tournament): array
    {
        $matches = $tournament->matches()
            ->where('status', '!=', TournamentMatch::STATUS_CANCELLED)
            ->whereNotNull('kickoff_time')
            ->get();

        $conflicts = [];

        foreach ($matches as $match) {
            $matchConflicts = $this->getMatchConflicts($match);

            if (!empty($matchConflicts)) {
                $conflicts[] = [
                    'match' => $match,
                    'conflicts' => $matchConflicts,
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Suggest optimal match time based on team preferences
     */
    public function suggestOptimalTime(Tournament $tournament, int $homeTeamId, int $awayTeamId, Carbon $startDate): ?Carbon
    {
        // Simple heuristic: find a slot where neither team plays
        // Can be extended with team preference data

        return $this->findAvailableSlot(
            $tournament,
            null,
            $startDate,
            $this->defaultMatchDuration
        );
    }

    /**
     * Generate ELO-based pairings for balanced matches
     */
    public function generateEloBasedPairings(Collection $teams): array
    {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->generateEloPairings($teams);
    }

    /**
     * Seed teams by ELO rating
     */
    public function seedByElo(Collection $teams): Collection
    {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->seedTeamsByElo($teams);
    }

    /**
     * Generate hybrid format (group stage + knockout)
     */
    public function generateHybridFormat(
        Tournament $tournament,
        int $teamsPerGroup = 4,
        int $teamsAdvance = 2,
        bool $includeThirdPlace = false
    ): array {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->generateHybridFormat(
            $tournament,
            $teamsPerGroup,
            $teamsAdvance,
            $includeThirdPlace
        );
    }

    /**
     * Auto-assign referees to a match
     */
    public function autoAssignReferees(TournamentMatch $match): array
    {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->autoAssignReferees($match);
    }

    /**
     * Suggest optimal venue for match based on travel
     */
    public function suggestOptimalVenue(
        Tournament $tournament,
        int $homeTeamId,
        int $awayTeamId,
        Carbon $dateTime
    ): ?TournamentVenue {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->suggestOptimalVenue($tournament, $homeTeamId, $awayTeamId, $dateTime);
    }

    /**
     * Optimize schedule for minimal travel
     */
    public function optimizeForTravel(Tournament $tournament): array
    {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->optimizeScheduleForTravel($tournament);
    }

    /**
     * Check for referee conflicts
     */
    public function checkRefereeConflict(Tournament $tournament, ?int $refereeId, Carbon $dateTime, int $duration = 90): bool
    {
        $smartEngine = new SmartTournamentEngine();
        return $smartEngine->checkRefereeConflict($tournament, $refereeId, $dateTime, $duration);
    }
}
