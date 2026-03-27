<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * TournamentMatch Model
 *
 * Extended with match type support, format configuration, timezone,
 * cancellation tracking, and comprehensive scheduling features
 */
class TournamentMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'home_team_id',
        'away_team_id',
        'pool_id',
        'venue_id',
        'match_type',
        'home_away',
        'venue',
        'kickoff_time',
        'timezone',
        'home_score',
        'away_score',
        'status',
        'match_day',
        'round',
        'scheduled_day',
        'scheduled_time',
        'notes',
        'match_format',
        'scoring_rules',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
        'original_kickoff_time',
        'started_at',
        'completed_at',
        'leg_number',
        'aggregate_match_id',
        'created_by',
    ];

    protected $casts = [
        'kickoff_time' => 'datetime',
        'original_kickoff_time' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'match_format' => 'array',
        'scoring_rules' => 'array',
    ];

    // Status constants
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_POSTPONED = 'postponed';
    const STATUS_CANCELLED = 'cancelled';

    // Match type constants
    const TYPE_TOURNAMENT = 'tournament';
    const TYPE_LEAGUE = 'league';
    const TYPE_FRIENDLY = 'friendly';
    const TYPE_KNOCKOUT = 'knockout';
    const TYPE_GROUP_STAGE = 'group_stage';
    const TYPE_EXHIBITION = 'exhibition';

    // Home/Away constants
    const HOME_AWAY_HOME = 'home';
    const HOME_AWAY_AWAY = 'away';
    const HOME_AWAY_NEUTRAL = 'neutral';

    /**
     * Get match type options for dropdowns
     */
    public static function getMatchTypes(): array
    {
        return [
            self::TYPE_TOURNAMENT => 'Tournament Match',
            self::TYPE_LEAGUE => 'League Match',
            self::TYPE_FRIENDLY => 'Friendly Match',
            self::TYPE_KNOCKOUT => 'Knockout Round',
            self::TYPE_GROUP_STAGE => 'Group Stage Match',
            self::TYPE_EXHIBITION => 'Exhibition Match',
        ];
    }

    /**
     * Get default match format by match type
     */
    public static function getDefaultFormat(string $matchType): array
    {
        return match ($matchType) {
            self::TYPE_KNOCKOUT => [
                'duration' => 90,
                'halves' => 2,
                'half_duration' => 45,
                'overtime_duration' => 30,
                'has_overtime' => true,
                'has_penalties' => true,
                'has_extra_time' => true,
            ],
            self::TYPE_GROUP_STAGE, self::TYPE_LEAGUE, self::TYPE_TOURNAMENT => [
                'duration' => 90,
                'halves' => 2,
                'half_duration' => 45,
                'overtime_duration' => 0,
                'has_overtime' => false,
                'has_penalties' => false,
                'has_extra_time' => false,
            ],
            self::TYPE_FRIENDLY, self::TYPE_EXHIBITION => [
                'duration' => 90,
                'halves' => 2,
                'half_duration' => 45,
                'overtime_duration' => 0,
                'has_overtime' => false,
                'has_penalties' => false,
                'has_extra_time' => false,
            ],
            default => [
                'duration' => 90,
                'halves' => 2,
                'half_duration' => 45,
                'overtime_duration' => 0,
                'has_overtime' => false,
                'has_penalties' => false,
                'has_extra_time' => false,
            ],
        };
    }

    /**
     * Get default scoring rules
     */
    public static function getDefaultScoringRules(): array
    {
        return [
            'win_points' => 3,
            'draw_points' => 1,
            'loss_points' => 0,
            'tiebreaker' => 'goal_difference', // goal_difference, head_to_head, goals_scored
            'penalties_worth' => 2,
        ];
    }

    /**
     * Get timezone options
     */
    public static function getTimezones(): array
    {
        return [
            'UTC' => 'UTC',
            'Africa/Nairobi' => 'East Africa (EAT)',
            'Europe/London' => 'UK (GMT/BST)',
            'Europe/Paris' => 'Central Europe (CET)',
            'America/New_York' => 'Eastern Time (EST/EDT)',
            'America/Los_Angeles' => 'Pacific Time (PST/PDT)',
            'Asia/Dubai' => 'Dubai (GST)',
            'Asia/Singapore' => 'Singapore (SGT)',
        ];
    }

    // Relationships
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'away_team_id');
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(TournamentPool::class, 'pool_id');
    }

    public function venueModel(): BelongsTo
    {
        return $this->belongsTo(TournamentVenue::class, 'venue_id');
    }

    /**
     * Get venue (alias for venueModel)
     */
    public function venue(): BelongsTo
    {
        return $this->venueModel();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function aggregateMatch(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'aggregate_match_id');
    }

    /**
     * Get the main referee for this match
     */
    public function referee(): BelongsTo
    {
        return $this->belongsTo(TournamentReferee::class, 'referee_id');
    }

    /**
     * Get first assistant referee
     */
    public function assistantReferee1(): BelongsTo
    {
        return $this->belongsTo(TournamentReferee::class, 'assistant_referee_1_id');
    }

    /**
     * Get second assistant referee
     */
    public function assistantReferee2(): BelongsTo
    {
        return $this->belongsTo(TournamentReferee::class, 'assistant_referee_2_id');
    }

    /**
     * Get fourth official
     */
    public function fourthOfficial(): BelongsTo
    {
        return $this->belongsTo(TournamentReferee::class, 'fourth_official_id');
    }

    /**
     * Get next match in bracket
     */
    public function nextMatch(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'next_match_id');
    }

    /**
     * Get loser next match (for double elimination)
     */
    public function loserNextMatch(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'loser_next_match_id');
    }

    /**
     * Get all match officials
     */
    public function getMatchOfficials(): array
    {
        return [
            'referee' => $this->referee,
            'assistant_1' => $this->assistantReferee1,
            'assistant_2' => $this->assistantReferee2,
            'fourth_official' => $this->fourthOfficial,
        ];
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePostponed($query)
    {
        return $query->where('status', self::STATUS_POSTPONED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeByMatchDay($query, $matchDay)
    {
        return $query->where('match_day', $matchDay);
    }

    public function scopeByRound($query, $round)
    {
        return $query->where('round', $round);
    }

    public function scopeByMatchType($query, $type)
    {
        return $query->where('match_type', $type);
    }

    public function scopeByPool($query, $poolId)
    {
        return $query->where('pool_id', $poolId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->where('kickoff_time', '>', now())
            ->orderBy('kickoff_time', 'asc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('kickoff_time', today());
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('kickoff_time', [$startDate, $endDate]);
    }

    public function scopeByVenue($query, $venueId)
    {
        return $query->where('venue_id', $venueId);
    }

    // Helper methods
    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if match is live (alias for isInProgress)
     */
    public function isLive(): bool
    {
        return $this->isInProgress();
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPostponed(): bool
    {
        return $this->status === self::STATUS_POSTPONED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canEdit(): bool
    {
        return in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_IN_PROGRESS]);
    }

    public function canStart(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function canCancel(): bool
    {
        return !$this->isCompleted() && !$this->isCancelled();
    }

    public function getWinner(): ?TournamentTeam
    {
        if (!$this->isCompleted()) {
            return null;
        }

        if ($this->home_score > $this->away_score) {
            return $this->homeTeam;
        } elseif ($this->away_score > $this->home_score) {
            return $this->awayTeam;
        }

        return null; // Draw
    }

    public function isDraw(): bool
    {
        return $this->isCompleted() && $this->home_score === $this->away_score;
    }

    /**
     * Get match type display name
     */
    public function getMatchTypeNameAttribute(): string
    {
        return self::getMatchTypes()[$this->match_type] ?? 'Unknown';
    }

    /**
     * Get match format display
     */
    public function getMatchFormatDisplayAttribute(): string
    {
        $format = $this->match_format ?? self::getDefaultFormat($this->match_type);
        return "{$format['duration']} min ({$format['halves']} x {$format['half_duration']} min)";
    }

    /**
     * Get formatted kickoff time with timezone
     */
    public function getFormattedKickoffTime(): string
    {
        if (!$this->kickoff_time) {
            return 'TBD';
        }

        $timezone = $this->timezone ?? 'UTC';
        return $this->kickoff_time->setTimezone($timezone)->format('M d, Y H:i');
    }

    /**
     * Record match result with optional overtime
     */
    public function recordResult(int $homeScore, int $awayScore, bool $afterOvertime = false): void
    {
        $this->update([
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // Recalculate standings
        $this->tournament->calculateStandings();
    }

    /**
     * Record match result and update ELO ratings
     */
    public function recordResultWithElo(int $homeScore, int $awayScore, bool $afterOvertime = false): array
    {
        $this->recordResult($homeScore, $awayScore, $afterOvertime);

        // Update ELO ratings if both teams exist
        if ($this->home_team_id && $this->away_team_id) {
            $homeTeam = TournamentTeam::find($this->home_team_id);
            $awayTeam = TournamentTeam::find($this->away_team_id);

            if ($homeTeam && $awayTeam) {
                $smartEngine = new \App\Services\SmartTournamentEngine();

                $isDraw = $homeScore === $awayScore;

                if ($isDraw) {
                    // Both teams get partial points
                    $homeChange = \App\Services\SmartTournamentEngine::ELO_K_FACTOR * 0.5 * (0.5 - (1 / (1 + pow(10, ($awayTeam->getEloRating() - $homeTeam->getEloRating()) / 400))));
                    $awayChange = \App\Services\SmartTournamentEngine::ELO_K_FACTOR * 0.5 * (0.5 - (1 / (1 + pow(10, ($homeTeam->getEloRating() - $awayTeam->getEloRating()) / 400))));

                    $homeTeam->update([
                        'elo_rating' => max(\App\Services\SmartTournamentEngine::ELO_MIN, min(\App\Services\SmartTournamentEngine::ELO_MAX, $homeTeam->getEloRating() + round($homeChange))),
                        'elo_matches' => ($homeTeam->elo_matches ?? 0) + 1,
                        'draws' => ($homeTeam->draws ?? 0) + 1,
                        'goals_for' => ($homeTeam->goals_for ?? 0) + $homeScore,
                        'goals_against' => ($homeTeam->goals_against ?? 0) + $awayScore,
                        'last_match_date' => now(),
                    ]);

                    $awayTeam->update([
                        'elo_rating' => max(\App\Services\SmartTournamentEngine::ELO_MIN, min(\App\Services\SmartTournamentEngine::ELO_MAX, $awayTeam->getEloRating() + round($awayChange))),
                        'elo_matches' => ($awayTeam->elo_matches ?? 0) + 1,
                        'draws' => ($awayTeam->draws ?? 0) + 1,
                        'goals_for' => ($awayTeam->goals_for ?? 0) + $awayScore,
                        'goals_against' => ($awayTeam->goals_against ?? 0) + $homeScore,
                        'last_match_date' => now(),
                    ]);
                } elseif ($homeScore > $awayScore) {
                    // Home team wins
                    $result = $smartEngine->updateTeamElo($homeTeam, $awayTeam, $homeScore, $awayScore);
                    return $result;
                } else {
                    // Away team wins
                    $result = $smartEngine->updateTeamElo($awayTeam, $homeTeam, $awayScore, $homeScore);
                    return $result;
                }
            }
        }

        return [];
    }

    /**
     * Start the match
     */
    public function startMatch(): void
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);
    }

    /**
     * Postpone the match with optional new date
     */
    public function postponeMatch(?\Carbon\Carbon $newDateTime = null): void
    {
        $data = ['status' => self::STATUS_POSTPONED];

        if ($newDateTime) {
            $data['original_kickoff_time'] = $this->kickoff_time;
            $data['kickoff_time'] = $newDateTime;
        }

        $this->update($data);
    }

    /**
     * Cancel the match with reason
     */
    public function cancelMatch(string $reason, ?int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancellation_reason' => $reason,
            'cancelled_by' => $userId ?? auth()->id(),
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Reschedule the match
     */
    public function reschedule(\Carbon\Carbon $newDateTime, string $timezone = 'UTC'): void
    {
        $this->update([
            'original_kickoff_time' => $this->kickoff_time,
            'kickoff_time' => $newDateTime,
            'timezone' => $timezone,
            'status' => self::STATUS_SCHEDULED,
        ]);
    }

    /**
     * Get score display
     */
    public function getScoreDisplay(): string
    {
        if (!$this->isCompleted() && !$this->isInProgress()) {
            return 'vs';
        }
        return "{$this->home_score} - {$this->away_score}";
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_SCHEDULED => 'info',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_POSTPONED => 'warning',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check for venue conflict
     */
    public function hasVenueConflict(): bool
    {
        if (!$this->venue_id || !$this->kickoff_time) {
            return false;
        }

        $format = $this->match_format ?? self::getDefaultFormat($this->match_type);
        $duration = $format['duration'] ?? 90;
        $endTime = $this->kickoff_time->copy()->addMinutes($duration);

        return self::where('id', '!=', $this->id)
            ->where('tournament_id', $this->tournament_id)
            ->where('venue_id', $this->venue_id)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->where(function ($query) use ($endTime) {
                $query->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL duration MINUTE) > ?', [$this->kickoff_time]);
            })
            ->exists();
    }

    /**
     * Check for team conflict
     */
    public function hasTeamConflict(): bool
    {
        if (!$this->kickoff_time) {
            return false;
        }

        $format = $this->match_format ?? self::getDefaultFormat($this->match_type);
        $duration = $format['duration'] ?? 90;
        $endTime = $this->kickoff_time->copy()->addMinutes($duration);

        return self::where('id', '!=', $this->id)
            ->where('tournament_id', $this->tournament_id)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->where(function ($query) use ($endTime) {
                $query->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL duration MINUTE) > ?', [$this->kickoff_time]);
            })
            ->where(function ($query) {
                $query->where('home_team_id', $this->home_team_id)
                    ->orWhere('home_team_id', $this->away_team_id)
                    ->orWhere('away_team_id', $this->home_team_id)
                    ->orWhere('away_team_id', $this->away_team_id);
            })
            ->exists();
    }

    /**
     * Generate fixtures for a tournament (round-robin)
     */
    public static function generateFixtures(Tournament $tournament): void
    {
        $teams = $tournament->approvedTeams()->get();

        if ($teams->count() < 2) {
            return;
        }

        // Simple round-robin: each team plays each other once
        $teamList = $teams->pluck('id')->toArray();
        $numTeams = count($teamList);
        $numRounds = $numTeams - 1;
        $matchesPerRound = $numTeams / 2;

        $matchDay = 1;

        // Generate fixtures for each round
        for ($round = 0; $round < $numRounds; $round++) {
            for ($i = 0; $i < $matchesPerRound; $i++) {
                $homeIndex = $i;
                $awayIndex = $numTeams - 1 - $i;

                if ($homeIndex < $numTeams && $awayIndex >= 0 && $homeIndex < $awayIndex) {
                    self::create([
                        'tournament_id' => $tournament->id,
                        'home_team_id' => $teamList[$homeIndex],
                        'away_team_id' => $teamList[$awayIndex],
                        'match_type' => self::TYPE_LEAGUE,
                        'match_day' => $matchDay,
                        'round' => $round + 1,
                        'status' => self::STATUS_SCHEDULED,
                        'match_format' => self::getDefaultFormat(self::TYPE_LEAGUE),
                        'scoring_rules' => self::getDefaultScoringRules(),
                    ]);
                }
            }

            // Rotate teams for next round (keep first team fixed)
            if ($numTeams > 2) {
                $lastTeam = array_pop($teamList);
                array_splice($teamList, 1, 0, $lastTeam);
            }

            $matchDay++;
        }
    }

    /**
     * Generate knockout fixtures
     */
    public static function generateKnockoutFixtures(Tournament $tournament, int $rounds = null): array
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
            $teamList[] = null; // Bye
        }

        // Shuffle for random seeding
        shuffle($teamList);

        $matches = [];
        $matchNumber = 1;

        // First round
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $homeTeam = $teamList[$i];
            $awayTeam = $teamList[$i + 1];

            // If one team has a bye
            if ($homeTeam === null) {
                $winner = $awayTeam;
                $awayTeam = null;
            } elseif ($awayTeam === null) {
                $winner = $homeTeam;
                $homeTeam = null;
            } else {
                $winner = null;
            }

            $match = self::create([
                'tournament_id' => $tournament->id,
                'home_team_id' => $homeTeam,
                'away_team_id' => $awayTeam,
                'match_type' => self::TYPE_KNOCKOUT,
                'round' => 1,
                'status' => $winner ? self::STATUS_COMPLETED : self::STATUS_SCHEDULED,
                'home_score' => $winner === $homeTeam ? 1 : 0,
                'away_score' => $winner === $awayTeam ? 1 : 0,
                'match_format' => self::getDefaultFormat(self::TYPE_KNOCKOUT),
            ]);

            $matches[] = $match;
            $matchNumber++;
        }

        return $matches;
    }

    /**
     * Generate group stage fixtures
     */
    public static function generateGroupStageFixtures(Tournament $tournament, int $teamsPerGroup = 4): array
    {
        $pools = $tournament->pools()->ordered()->get();
        $matches = [];

        foreach ($pools as $pool) {
            $teams = $pool->teams()->get();

            if ($teams->count() < 2) {
                continue;
            }

            $teamList = $teams->pluck('id')->toArray();
            $numTeams = count($teamList);
            $numRounds = $numTeams - 1;
            $matchesPerRound = $numTeams / 2;

            $round = 1;

            for ($r = 0; $r < $numRounds; $r++) {
                for ($i = 0; $i < $matchesPerRound; $i++) {
                    $homeIndex = $i;
                    $awayIndex = $numTeams - 1 - $i;

                    if ($homeIndex < $numTeams && $awayIndex >= 0 && $homeIndex < $awayIndex) {
                        $match = self::create([
                            'tournament_id' => $tournament->id,
                            'home_team_id' => $teamList[$homeIndex],
                            'away_team_id' => $teamList[$awayIndex],
                            'pool_id' => $pool->id,
                            'match_type' => self::TYPE_GROUP_STAGE,
                            'round' => $round,
                            'status' => self::STATUS_SCHEDULED,
                            'match_format' => self::getDefaultFormat(self::TYPE_GROUP_STAGE),
                        ]);

                        $matches[] = $match;
                    }
                }

                // Rotate teams
                if ($numTeams > 2) {
                    $lastTeam = array_pop($teamList);
                    array_splice($teamList, 1, 0, $lastTeam);
                }

                $round++;
            }
        }

        return $matches;
    }

    /**
     * Record cards for the match
     */
    public function recordCards(
        int $homeYellow = 0,
        int $awayYellow = 0,
        int $homeRed = 0,
        int $awayRed = 0,
        array $cardDetails = []
    ): void {
        $this->update([
            'home_yellow_cards' => $homeYellow,
            'away_yellow_cards' => $awayYellow,
            'home_red_cards' => $homeRed,
            'away_red_cards' => $awayRed,
            'card_details' => $cardDetails,
        ]);

        // Create disciplinary cases for red cards
        if ($homeRed > 0 && $this->home_team_id) {
            $this->createAutomaticCase($this->home_team_id, $homeRed);
        }

        if ($awayRed > 0 && $this->away_team_id) {
            $this->createAutomaticCase($this->away_team_id, $awayRed);
        }
    }

    /**
     * Create automatic disciplinary case for red card
     */
    protected function createAutomaticCase(int $teamId, int $redCards): void
    {
        // Check if player has existing suspensions
        if (class_exists('App\Models\PlayerSuspension')) {
            // This would create a case - implementation depends on having the player_id
            // Placeholder for automatic case creation
        }
    }

    /**
     * Get total yellow cards in match
     */
    public function getTotalYellowCards(): int
    {
        return ($this->home_yellow_cards ?? 0) + ($this->away_yellow_cards ?? 0);
    }

    /**
     * Get total red cards in match
     */
    public function getTotalRedCards(): int
    {
        return ($this->home_red_cards ?? 0) + ($this->away_red_cards ?? 0);
    }

    /**
     * Check if match has any cards
     */
    public function hasCards(): bool
    {
        return $this->getTotalYellowCards() > 0 || $this->getTotalRedCards() > 0;
    }
}
