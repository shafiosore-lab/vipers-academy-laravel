<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use App\Models\TournamentVenue;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * TournamentSchedulingService
 *
 * Comprehensive tournament scheduling with:
 * - Tournament date validation
 * - Configurable time slots generation
 * - Fair distribution across available slots
 * - Constraint-based scheduling (max games/day, rest periods)
 * - Venue availability checking
 * - Team conflict prevention
 * - Timezone handling
 * - Conflict notifications
 */
class TournamentSchedulingService
{
    /**
     * Default time slots (24-hour format)
     */
    protected array $defaultTimeSlots = [
        '08:00', '09:00', '10:00', '11:00', '12:00',
        '13:00', '14:00', '15:00', '16:00', '17:00',
        '18:00', '19:00', '20:00'
    ];

    /**
     * Default match duration in minutes
     */
    protected int $defaultMatchDuration = 90;

    /**
     * Default buffer time between matches in minutes
     */
    protected int $defaultBufferTime = 15;

    /**
     * Minimum rest hours between matches for the same team
     */
    protected int $minRestHours = 24;

    /**
     * Maximum games per venue per day
     */
    protected int $maxGamesPerVenuePerDay = 4;

    /**
     * Maximum games per team per day
     */
    protected int $maxGamesPerTeamPerDay = 1;

    /**
     * Scheduling configuration
     */
    protected array $config = [];

    /**
     * Validation errors and warnings
     */
    protected array $validationErrors = [];
    protected array $validationWarnings = [];

    /**
     * Initialize the service with optional configuration
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'match_duration' => $this->defaultMatchDuration,
            'buffer_time' => $this->defaultBufferTime,
            'min_rest_hours' => $this->minRestHours,
            'max_games_per_venue_per_day' => $this->maxGamesPerVenuePerDay,
            'max_games_per_team_per_day' => $this->maxGamesPerTeamPerDay,
            'time_slots' => $this->defaultTimeSlots,
            'timezone' => 'Africa/Nairobi',
        ], $config);
    }

    /**
     * Validate tournament has required dates for scheduling
     */
    public function validateTournamentDates(Tournament $tournament): array
    {
        $this->validationErrors = [];
        $this->validationWarnings = [];

        // Check start date exists
        if (!$tournament->start_date) {
            $this->validationErrors[] = 'Tournament start date is required for scheduling';
        }

        // Check end date exists
        if (!$tournament->end_date) {
            $this->validationErrors[] = 'Tournament end date is required for scheduling';
        }

        // Check start date is in the future (or today)
        if ($tournament->start_date && Carbon::parse($tournament->start_date)->isPast() &&
            !Carbon::parse($tournament->start_date)->isToday()) {
            $this->validationWarnings[] = 'Tournament start date is in the past';
        }

        // Check end date is after start date
        if ($tournament->start_date && $tournament->end_date) {
            if (Carbon::parse($tournament->end_date)->isBefore(Carbon::parse($tournament->start_date))) {
                $this->validationErrors[] = 'End date must be after start date';
            }

            // Calculate tournament duration
            $days = Carbon::parse($tournament->start_date)->diffInDays(Carbon::parse($tournament->end_date));
            $this->validationWarnings[] = "Tournament duration: {$days} days";
        }

        return [
            'valid' => empty($this->validationErrors),
            'errors' => $this->validationErrors,
            'warnings' => $this->validationWarnings,
        ];
    }

    /**
     * Generate available time slots within tournament date range
     */
    public function generateTimeSlots(Tournament $tournament, ?array $customSlots = null): Collection
    {
        $slots = collect();
        $timeSlots = $customSlots ?? $this->config['time_slots'];

        if (!$tournament->start_date || !$tournament->end_date) {
            return $slots;
        }

        $startDate = Carbon::parse($tournament->start_date);
        $endDate = Carbon::parse($tournament->end_date);
        $matchDuration = $this->config['match_duration'];
        $bufferTime = $this->config['buffer_time'];
        $totalMatchTime = $matchDuration + $bufferTime;

        // Generate slots for each day in the range
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            foreach ($timeSlots as $time) {
                $slotDateTime = $currentDate->copy()->setTimeFromTimeString($time);

                // Skip past times if start date is today
                if ($currentDate->isToday() && $slotDateTime->isPast()) {
                    continue;
                }

                $slots->push([
                    'datetime' => $slotDateTime,
                    'date' => $slotDateTime->format('Y-m-d'),
                    'time' => $time,
                    'end_datetime' => $slotDateTime->copy()->addMinutes($totalMatchTime),
                    'available' => true,
                ]);
            }
            $currentDate->addDay();
        }

        return $slots;
    }

    /**
     * Get available time slots excluding booked times
     */
    public function getAvailableTimeSlots(Tournament $tournament, ?int $venueId = null): Collection
    {
        $allSlots = $this->generateTimeSlots($tournament);

        if ($allSlots->isEmpty()) {
            return $allSlots;
        }

        // Get existing matches
        $existingMatches = $tournament->matches()
            ->whereNotNull('kickoff_time')
            ->where('status', '!=', TournamentMatch::STATUS_CANCELLED)
            ->get();

        // Mark slots as unavailable if they conflict with existing matches
        return $allSlots->map(function ($slot) use ($existingMatches, $venueId) {
            $slotEnd = $slot['end_datetime'];
            $slotStart = $slot['datetime'];

            foreach ($existingMatches as $match) {
                if ($venueId && $match->venue_id != $venueId) {
                    continue;
                }

                $matchEnd = $match->kickoff_time->copy()->addMinutes(
                    $match->match_format['duration'] ?? $this->config['match_duration']
                );

                // Check for overlap
                if ($slotStart->lt($matchEnd) && $slotEnd->gt($match->kickoff_time)) {
                    $slot['available'] = false;
                    $slot['conflict_with'] = $match->id;
                    break;
                }
            }

            return $slot;
        });
    }

    /**
     * Check if a specific time slot is available for a team
     */
    public function isTeamAvailableAt(Tournament $tournament, int $teamId, Carbon $dateTime): bool
    {
        $matchDuration = $this->config['match_duration'];
        $endTime = $dateTime->copy()->addMinutes($matchDuration);
        $minRestTime = $dateTime->copy()->subHours($this->config['min_rest_hours']);

        // Check if team has any match at this time
        $hasConflict = $tournament->matches()
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->whereNotNull('kickoff_time')
            ->where(function ($query) use ($teamId, $dateTime, $endTime) {
                $query->where('home_team_id', $teamId)
                    ->orWhere('away_team_id', $teamId);
            })
            ->where(function ($query) use ($dateTime, $endTime) {
                $query->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            })
            ->exists();

        if ($hasConflict) {
            return false;
        }

        // Check minimum rest time from last match
        $lastMatch = $tournament->matches()
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->where(function ($query) use ($teamId) {
                $query->where('home_team_id', $teamId)
                    ->orWhere('away_team_id', $teamId);
            })
            ->where('kickoff_time', '<', $dateTime)
            ->orderBy('kickoff_time', 'desc')
            ->first();

        if ($lastMatch) {
            $matchEnd = $lastMatch->kickoff_time->copy()->addMinutes(
                $lastMatch->match_format['duration'] ?? $this->config['match_duration']
            );
            $hoursSinceLastMatch = $matchEnd->diffInHours($dateTime);

            if ($hoursSinceLastMatch < $this->config['min_rest_hours']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if venue is available at specific time
     */
    public function isVenueAvailableAt(Tournament $tournament, ?int $venueId, Carbon $dateTime): bool
    {
        if (!$venueId) {
            return true;
        }

        $matchDuration = $this->config['match_duration'];
        $endTime = $dateTime->copy()->addMinutes($matchDuration);

        return !$tournament->matches()
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->where('venue_id', $venueId)
            ->whereNotNull('kickoff_time')
            ->where(function ($query) use ($dateTime, $endTime) {
                $query->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            })
            ->exists();
    }

    /**
     * Count games scheduled at venue on a specific date
     */
    public function getVenueGamesCountForDate(Tournament $tournament, int $venueId, string $date): int
    {
        return $tournament->matches()
            ->where('venue_id', $venueId)
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->whereDate('kickoff_time', $date)
            ->count();
    }

    /**
     * Count games scheduled for team on a specific date
     */
    public function getTeamGamesCountForDate(Tournament $tournament, int $teamId, string $date): int
    {
        return $tournament->matches()
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->where(function ($query) use ($teamId) {
                $query->where('home_team_id', $teamId)
                    ->orWhere('away_team_id', $teamId);
            })
            ->whereDate('kickoff_time', $date)
            ->count();
    }

    /**
     * Schedule matches with fair distribution and constraints
     */
    public function scheduleMatchesWithConstraints(
        Tournament $tournament,
        array $matches,
        ?int $defaultVenueId = null,
        bool $randomize = true
    ): array {
        $scheduled = [];
        $conflicts = [];

        // Shuffle matches if randomization is enabled
        if ($randomize) {
            shuffle($matches);
        }

        // Get available slots
        $availableSlots = $this->getAvailableTimeSlots($tournament, $defaultVenueId)
            ->filter(fn($slot) => $slot['available'])
            ->values();

        if ($availableSlots->isEmpty()) {
            $this->validationErrors[] = 'No available time slots found';
            return ['scheduled' => [], 'conflicts' => $conflicts, 'errors' => $this->validationErrors];
        }

        // Track scheduled matches per day per venue
        $venueDayCounts = [];
        $teamDayCounts = [];

        foreach ($matches as $matchData) {
            $homeTeamId = $matchData['home_team_id'] ?? null;
            $awayTeamId = $matchData['away_team_id'] ?? null;
            $venueId = $matchData['venue_id'] ?? $defaultVenueId;

            $scheduledMatch = false;

            // Try to find an available slot
            foreach ($availableSlots as $index => $slot) {
                $slotDate = $slot['date'];
                $slotDateTime = $slot['datetime'];

                // Check venue daily limit
                if ($venueId) {
                    $venueKey = "{$venueId}_{$slotDate}";
                    $venueDayCounts[$venueKey] = ($venueDayCounts[$venueKey] ?? 0) + 1;

                    if ($venueDayCounts[$venueKey] > $this->config['max_games_per_venue_per_day']) {
                        continue;
                    }
                }

                // Check team daily limits
                $teamHasCapacity = true;
                if ($homeTeamId) {
                    $homeKey = "{$homeTeamId}_{$slotDate}";
                    $teamDayCounts[$homeKey] = ($teamDayCounts[$homeKey] ?? 0) + 1;
                    if ($teamDayCounts[$homeKey] > $this->config['max_games_per_team_per_day']) {
                        $teamHasCapacity = false;
                    }
                }

                if ($awayTeamId) {
                    $awayKey = "{$awayTeamId}_{$slotDate}";
                    $teamDayCounts[$awayKey] = ($teamDayCounts[$awayKey] ?? 0) + 1;
                    if ($teamDayCounts[$awayKey] > $this->config['max_games_per_team_per_day']) {
                        $teamHasCapacity = false;
                    }
                }

                if (!$teamHasCapacity) {
                    continue;
                }

                // Check team availability
                if ($homeTeamId && !$this->isTeamAvailableAt($tournament, $homeTeamId, $slotDateTime)) {
                    continue;
                }

                if ($awayTeamId && !$this->isTeamAvailableAt($tournament, $awayTeamId, $slotDateTime)) {
                    continue;
                }

                // Check venue availability
                if ($venueId && !$this->isVenueAvailableAt($tournament, $venueId, $slotDateTime)) {
                    continue;
                }

                // Found a valid slot - create the match
                $match = TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'home_team_id' => $homeTeamId,
                    'away_team_id' => $awayTeamId,
                    'venue_id' => $venueId,
                    'venue' => $venueId ? TournamentVenue::find($venueId)?->name : null,
                    'kickoff_time' => $slotDateTime,
                    'timezone' => $this->config['timezone'],
                    'match_type' => $matchData['match_type'] ?? TournamentMatch::TYPE_LEAGUE,
                    'pool_id' => $matchData['pool_id'] ?? null,
                    'round' => $matchData['round'] ?? 1,
                    'match_day' => $matchData['match_day'] ?? 1,
                    'status' => TournamentMatch::STATUS_SCHEDULED,
                    'match_format' => $matchData['match_format'] ?? TournamentMatch::getDefaultFormat($matchData['match_type'] ?? TournamentMatch::TYPE_LEAGUE),
                    'scoring_rules' => TournamentMatch::getDefaultScoringRules(),
                ]);

                $scheduled[] = $match;
                $scheduledMatch = true;

                // Mark this slot as used
                $availableSlots->forget($index);
                break;
            }

            if (!$scheduledMatch) {
                $conflicts[] = [
                    'match' => $matchData,
                    'reason' => 'No available slot found matching all constraints',
                ];
            }
        }

        return [
            'scheduled' => $scheduled,
            'conflicts' => $conflicts,
            'errors' => $this->validationErrors,
        ];
    }

    /**
     * Generate league schedule with fair distribution
     */
    public function generateFairLeagueSchedule(Tournament $tournament, ?int $venueId = null): array
    {
        $teams = $tournament->approvedTeams()->get();

        if ($teams->count() < 2) {
            return ['error' => 'At least 2 teams required'];
        }

        // Generate fixtures
        $teamList = $teams->pluck('id')->toArray();
        $numTeams = count($teamList);

        // Handle odd number of teams
        if ($numTeams % 2 !== 0) {
            $teamList[] = null;
            $numTeams++;
        }

        $matchesPerRound = $numTeams / 2;
        $numRounds = $numTeams - 1;
        $matches = [];

        for ($round = 0; $round < $numRounds; $round++) {
            for ($i = 0; $i < $matchesPerRound; $i++) {
                $homeIndex = $i;
                $awayIndex = $numTeams - 1 - $i;

                $homeTeamId = $teamList[$homeIndex] ?? null;
                $awayTeamId = $teamList[$awayIndex] ?? null;

                if (!$homeTeamId || !$awayTeamId) {
                    continue;
                }

                $matches[] = [
                    'home_team_id' => $homeTeamId,
                    'away_team_id' => $awayTeamId,
                    'venue_id' => $venueId,
                    'match_type' => TournamentMatch::TYPE_LEAGUE,
                    'round' => $round + 1,
                    'match_day' => $round + 1,
                ];
            }

            // Rotate teams
            if ($numTeams > 2) {
                $lastTeam = array_pop($teamList);
                array_splice($teamList, 1, 0, $lastTeam);
            }
        }

        // Schedule with constraints
        return $this->scheduleMatchesWithConstraints($tournament, $matches, $venueId, true);
    }

    /**
     * Generate group stage schedule with fair distribution
     */
    public function generateFairGroupStageSchedule(Tournament $tournament, ?int $venueId = null): array
    {
        $pools = $tournament->pools()->ordered()->get();

        if ($pools->count() === 0) {
            return ['error' => 'No pools defined'];
        }

        $allMatches = [];

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

                    $allMatches[] = [
                        'home_team_id' => $homeTeamId,
                        'away_team_id' => $awayTeamId,
                        'venue_id' => $venueId,
                        'pool_id' => $pool->id,
                        'match_type' => TournamentMatch::TYPE_GROUP_STAGE,
                        'round' => $round,
                        'match_day' => $round,
                    ];
                }

                // Rotate teams
                if ($numTeams > 2) {
                    $lastTeam = array_pop($teamList);
                    array_splice($teamList, 1, 0, $lastTeam);
                }
            }
        }

        return $this->scheduleMatchesWithConstraints($tournament, $allMatches, $venueId, true);
    }

    /**
     * Generate knockout schedule with constraints
     */
    public function generateFairKnockoutSchedule(Tournament $tournament, ?int $venueId = null, ?int $rounds = null): array
    {
        $teams = $tournament->approvedTeams()->get();

        if ($teams->count() < 2) {
            return ['error' => 'At least 2 teams required'];
        }

        $numTeams = $teams->count();
        $rounds = $rounds ?? (int)ceil(log2($numTeams));
        $bracketSize = pow(2, $rounds);

        // Pad with byes
        $teamList = $teams->pluck('id')->toArray();
        while (count($teamList) < $bracketSize) {
            $teamList[] = null;
        }

        shuffle($teamList);

        $matches = [];

        // First round
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $homeTeam = $teamList[$i];
            $awayTeam = $teamList[$i + 1];

            // Handle byes
            if ($homeTeam === null || $awayTeam === null) {
                continue;
            }

            $matches[] = [
                'home_team_id' => $homeTeam,
                'away_team_id' => $awayTeam,
                'venue_id' => $venueId,
                'match_type' => TournamentMatch::TYPE_KNOCKOUT,
                'round' => 1,
            ];
        }

        // Generate subsequent rounds (placeholders)
        for ($round = 2; $round <= $rounds; $round++) {
            $numMatches = $bracketSize / pow(2, $round);
            for ($i = 0; $i < $numMatches; $i++) {
                $matches[] = [
                    'home_team_id' => null,
                    'away_team_id' => null,
                    'venue_id' => $venueId,
                    'match_type' => TournamentMatch::TYPE_KNOCKOUT,
                    'round' => $round,
                ];
            }
        }

        return $this->scheduleMatchesWithConstraints($tournament, $matches, $venueId, true);
    }

    /**
     * Check all scheduling constraints and return violations
     */
    public function checkSchedulingConstraints(Tournament $tournament): array
    {
        $violations = [];
        $matches = $tournament->matches()
            ->whereNotNull('kickoff_time')
            ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
            ->get();

        // Group matches by date
        $matchesByDate = $matches->groupBy(fn($m) => $m->kickoff_time->format('Y-m-d'));

        foreach ($matches as $match) {
            $matchDate = $match->kickoff_time->format('Y-m-d');
            $matchDateTime = $match->kickoff_time;
            $matchEnd = $matchDateTime->copy()->addMinutes(
                $match->match_format['duration'] ?? $this->config['match_duration']
            );

            // Check venue conflicts
            if ($match->venue_id) {
                $venueConflicts = $matches->filter(function ($m) use ($match, $matchDateTime, $matchEnd) {
                    if ($m->id === $match->id || !$m->venue_id || !$m->kickoff_time) {
                        return false;
                    }
                    $mEnd = $m->kickoff_time->copy()->addMinutes(
                        $m->match_format['duration'] ?? $this->config['match_duration']
                    );
                    return $m->venue_id === $match->venue_id &&
                           $m->kickoff_time->lt($matchEnd) &&
                           $mEnd->gt($matchDateTime);
                });

                if ($venueConflicts->isNotEmpty()) {
                    $violations[] = [
                        'type' => 'venue_conflict',
                        'match_id' => $match->id,
                        'message' => "Venue conflict: {$match->venue} has overlapping matches",
                        'severity' => 'error',
                    ];
                }
            }

            // Check team conflicts
            $teamIds = array_filter([$match->home_team_id, $match->away_team_id]);
            foreach ($teamIds as $teamId) {
                $teamConflicts = $matches->filter(function ($m) use ($match, $teamId, $matchDateTime, $matchEnd) {
                    if ($m->id === $match->id || !$m->kickoff_time) {
                        return false;
                    }
                    if ($m->home_team_id !== $teamId && $m->away_team_id !== $teamId) {
                        return false;
                    }
                    $mEnd = $m->kickoff_time->copy()->addMinutes(
                        $m->match_format['duration'] ?? $this->config['match_duration']
                    );
                    return $m->kickoff_time->lt($matchEnd) && $mEnd->gt($matchDateTime);
                });

                if ($teamConflicts->isNotEmpty()) {
                    $violations[] = [
                        'type' => 'team_conflict',
                        'match_id' => $match->id,
                        'team_id' => $teamId,
                        'message' => "Team has overlapping matches",
                        'severity' => 'error',
                    ];
                }
            }

            // Check rest period violations
            foreach ($teamIds as $teamId) {
                $lastMatch = $tournament->matches()
                    ->whereNotIn('status', [TournamentMatch::STATUS_CANCELLED])
                    ->where(function ($q) use ($teamId) {
                        $q->where('home_team_id', $teamId)
                            ->orWhere('away_team_id', $teamId);
                    })
                    ->where('kickoff_time', '<', $matchDateTime)
                    ->where('id', '!=', $match->id)
                    ->orderBy('kickoff_time', 'desc')
                    ->first();

                if ($lastMatch) {
                    $lastMatchEnd = $lastMatch->kickoff_time->copy()->addMinutes(
                        $lastMatch->match_format['duration'] ?? $this->config['match_duration']
                    );
                    $hoursRest = $lastMatchEnd->diffInHours($matchDateTime);

                    if ($hoursRest < $this->config['min_rest_hours']) {
                        $violations[] = [
                            'type' => 'rest_period',
                            'match_id' => $match->id,
                            'team_id' => $teamId,
                            'message' => "Team has less than {$this->config['min_rest_hours']}h rest between matches",
                            'severity' => 'warning',
                            'hours_rest' => $hoursRest,
                        ];
                    }
                }
            }

            // Check max games per venue per day
            if ($match->venue_id) {
                $venueGamesToday = $matchesByDate[$matchDate]
                    ->where('venue_id', $match->venue_id)
                    ->count() - 1; // Exclude current match

                if ($venueGamesToday >= $this->config['max_games_per_venue_per_day']) {
                    $violations[] = [
                        'type' => 'venue_capacity',
                        'match_id' => $match->id,
                        'message' => "Venue exceeds maximum games per day ({$this->config['max_games_per_venue_per_day']})",
                        'severity' => 'warning',
                    ];
                }
            }

            // Check max games per team per day
            foreach ($teamIds as $teamId) {
                $teamGamesToday = $matchesByDate[$matchDate]
                    ->filter(fn($m) => $m->home_team_id === $teamId || $m->away_team_id === $teamId)
                    ->count() - 1;

                if ($teamGamesToday >= $this->config['max_games_per_team_per_day']) {
                    $violations[] = [
                        'type' => 'team_capacity',
                        'match_id' => $match->id,
                        'team_id' => $teamId,
                        'message' => "Team has more than {$this->config['max_games_per_team_per_day']} game(s) on this day",
                        'severity' => 'warning',
                    ];
                }
            }
        }

        return $violations;
    }

    /**
     * Update match schedule (bulk)
     */
    public function bulkUpdateSchedule(Tournament $tournament, array $updates): array
    {
        $updated = [];
        $errors = [];

        foreach ($updates as $update) {
            $matchId = $update['match_id'] ?? null;
            $kickoffTime = $update['kickoff_time'] ?? null;
            $venueId = $update['venue_id'] ?? null;

            if (!$matchId) {
                $errors[] = 'Match ID is required';
                continue;
            }

            $match = $tournament->matches()->find($matchId);

            if (!$match) {
                $errors[] = "Match #{$matchId} not found";
                continue;
            }

            if (!$match->isScheduled()) {
                $errors[] = "Match #{$matchId} cannot be rescheduled (status: {$match->status})";
                continue;
            }

            // Validate new time
            if ($kickoffTime) {
                $newTime = Carbon::parse($kickoffTime);

                // Check venue availability
                if ($venueId && !$this->isVenueAvailableAt($tournament, $venueId, $newTime)) {
                    $errors[] = "Match #{$matchId}: Venue not available at requested time";
                    continue;
                }

                // Check team availability
                if ($match->home_team_id && !$this->isTeamAvailableAt($tournament, $match->home_team_id, $newTime)) {
                    $errors[] = "Match #{$matchId}: Home team not available at requested time";
                    continue;
                }

                if ($match->away_team_id && !$this->isTeamAvailableAt($tournament, $match->away_team_id, $newTime)) {
                    $errors[] = "Match #{$matchId}: Away team not available at requested time";
                    continue;
                }

                $match->kickoff_time = $newTime;
                $match->timezone = $this->config['timezone'];
            }

            if ($venueId) {
                $venue = TournamentVenue::find($venueId);
                $match->venue_id = $venueId;
                $match->venue = $venue?->name;
            }

            $match->save();
            $updated[] = $match->id;
        }

        return [
            'updated' => $updated,
            'errors' => $errors,
        ];
    }

    /**
     * Get scheduling statistics
     */
    public function getSchedulingStats(Tournament $tournament): array
    {
        $matches = $tournament->matches();

        $scheduled = $matches->whereNotNull('kickoff_time')->count();
        $unscheduled = $matches->whereNull('kickoff_time')->count();

        $byStatus = $matches->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Games per day distribution
        $matchesWithTimes = $matches->whereNotNull('kickoff_time')->get();
        $gamesPerDay = $matchesWithTimes->groupBy(fn($m) => $m->kickoff_time->format('Y-m-d'))
            ->map(fn($day) => $day->count());

        return [
            'total_matches' => $matches->count(),
            'scheduled' => $scheduled,
            'unscheduled' => $unscheduled,
            'by_status' => $byStatus,
            'games_per_day' => $gamesPerDay->toArray(),
            'min_date' => $matchesWithTimes->min('kickoff_time'),
            'max_date' => $matchesWithTimes->max('kickoff_time'),
        ];
    }

    /**
     * Set custom time slots
     */
    public function setTimeSlots(array $slots): self
    {
        $this->config['time_slots'] = $slots;
        return $this;
    }

    /**
     * Set match duration
     */
    public function setMatchDuration(int $minutes): self
    {
        $this->config['match_duration'] = $minutes;
        return $this;
    }

    /**
     * Set buffer time between matches
     */
    public function setBufferTime(int $minutes): self
    {
        $this->config['buffer_time'] = $minutes;
        return $this;
    }

    /**
     * Set minimum rest hours between matches
     */
    public function setMinRestHours(int $hours): self
    {
        $this->config['min_rest_hours'] = $hours;
        return $this;
    }

    /**
     * Set max games per venue per day
     */
    public function setMaxGamesPerVenuePerDay(int $count): self
    {
        $this->config['max_games_per_venue_per_day'] = $count;
        return $this;
    }

    /**
     * Set max games per team per day
     */
    public function setMaxGamesPerTeamPerDay(int $count): self
    {
        $this->config['max_games_per_team_per_day'] = $count;
        return $this;
    }

    /**
     * Set timezone
     */
    public function setTimezone(string $timezone): self
    {
        $this->config['timezone'] = $timezone;
        return $this;
    }

    /**
     * Get current configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
