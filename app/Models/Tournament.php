<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'season',
        'description',
        'registration_deadline',
        'squad_limit',
        'min_players',
        'max_teams',
        'competition_format',
        'estimated_matches',
        'status',
        'start_date',
        'end_date',
        'venue',
        'rules',
        'logo',
        'is_public',
        'created_by',
    ];

    protected $casts = [
        'registration_deadline' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_public' => 'boolean',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Competition Format constants
    const FORMAT_LEAGUE = 'league';
    const FORMAT_LEAGUE_CUP = 'league_cup';
    const FORMAT_KNOCKOUT = 'knockout';
    const FORMAT_KNOCKOUT_PLUS = 'knockout_plus';
    const FORMAT_GROUPS_KNOCKOUT = 'groups_knockout';
    const FORMAT_DOUBLE_ELIMINATION = 'double_elimination';
    const FORMAT_ROUND_ROBIN = 'round_robin';

    // Competition format options with metadata
    const COMPETITION_FORMATS = [
        self::FORMAT_LEAGUE => [
            'name' => 'League',
            'description' => 'All teams play each other. Points awarded for wins/draws.',
            'matches_per_team' => 'n-1',
            'total_matches' => 'n(n-1)/2',
            'has_standings' => true,
            'has_groups' => false,
        ],
        self::FORMAT_LEAGUE_CUP => [
            'name' => 'League + Cup',
            'description' => 'League stage followed by knockout cup final.',
            'matches_per_team' => 'n-1 + knockout',
            'total_matches' => 'n(n-1)/2 + (n-1)',
            'has_standings' => true,
            'has_groups' => false,
        ],
        self::FORMAT_KNOCKOUT => [
            'name' => 'Knockout (Single Elimination)',
            'description' => 'Teams eliminated after one loss. Direct knockout from quarterfinals.',
            'matches_per_team' => '1 to log2(n)',
            'total_matches' => 'n-1',
            'has_standings' => false,
            'has_groups' => false,
        ],
        self::FORMAT_KNOCKOUT_PLUS => [
            'name' => 'Knockout with Third Place',
            'description' => 'Knockout tournament with third place playoff match.',
            'matches_per_team' => '1 to log2(n)',
            'total_matches' => 'n + 1',
            'has_standings' => false,
            'has_groups' => false,
        ],
        self::FORMAT_GROUPS_KNOCKOUT => [
            'name' => 'Groups + Knockout',
            'description' => 'Teams divided into groups, top teams advance to knockout stage.',
            'matches_per_team' => 'group stage + knockout',
            'total_matches' => 'group_matches + knockout_matches',
            'has_standings' => true,
            'has_groups' => true,
        ],
        self::FORMAT_DOUBLE_ELIMINATION => [
            'name' => 'Double Elimination',
            'description' => 'Teams eliminated after two losses. Winner/winner and loser brackets.',
            'matches_per_team' => '2 to 2(n-1)',
            'total_matches' => '2n - 1 + loser_bracket',
            'has_standings' => false,
            'has_groups' => false,
        ],
        self::FORMAT_ROUND_ROBIN => [
            'name' => 'Round Robin',
            'description' => 'Each team plays every other team once (home and away).',
            'matches_per_team' => '2(n-1)',
            'total_matches' => 'n(n-1)',
            'has_standings' => true,
            'has_groups' => false,
        ],
    ];

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class);
    }

    public function approvedTeams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class)->where('approval_status', 'approved');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class);
    }

    public function standings(): HasMany
    {
        return $this->hasMany(TournamentStanding::class);
    }

    public function squads(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            TournamentSquad::class,
            TournamentTeam::class
        );
    }

    public function pools(): HasMany
    {
        return $this->hasMany(TournamentPool::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(TournamentVenue::class);
    }

    public function referees(): HasMany
    {
        return $this->hasMany(TournamentReferee::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_ONGOING]);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', self::STATUS_ONGOING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Helper methods
    public function isRegistrationOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isRegistrationDeadlinePassed(): bool
    {
        if (!$this->registration_deadline) {
            return false;
        }
        return Carbon::now()->greaterThan($this->registration_deadline);
    }

    public function canRegister(): bool
    {
        return $this->isRegistrationOpen() && !$this->isRegistrationDeadlinePassed();
    }

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isLocked(): bool
    {
        return in_array($this->status, [self::STATUS_ONGOING, self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function getRegisteredTeamsCount(): int
    {
        return $this->teams()->count();
    }

    public function getApprovedTeamsCount(): int
    {
        return $this->teams()->where('approval_status', 'approved')->count();
    }

    public function hasCapacity(): bool
    {
        if (!$this->max_teams) {
            return true;
        }
        return $this->getApprovedTeamsCount() < $this->max_teams;
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_OPEN => 'success',
            self::STATUS_CLOSED => 'warning',
            self::STATUS_ONGOING => 'primary',
            self::STATUS_COMPLETED => 'info',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    public static function generateSlug(string $name): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Calculate comprehensive standings for the tournament
     * Includes: wins, draws, losses, goals, points, cards, clean sheets, home/away breakdown, form
     */
    public function calculateStandings(): void
    {
        // Check if tournament format supports standings
        if (!$this->hasStandings()) {
            return;
        }

        $teams = $this->teams()->where('approval_status', 'approved')->get();
        $standingsData = [];

        // Get point system from match format or use defaults
        $winPoints = 3;
        $drawPoints = 1;
        $lossPoints = 0;

        // Check first match for scoring rules
        $sampleMatch = $this->matches()->where('status', 'completed')->first();
        if ($sampleMatch && !empty($sampleMatch->scoring_rules)) {
            $winPoints = $sampleMatch->scoring_rules['win_points'] ?? 3;
            $drawPoints = $sampleMatch->scoring_rules['draw_points'] ?? 1;
            $lossPoints = $sampleMatch->scoring_rules['loss_points'] ?? 0;
        }

        foreach ($teams as $team) {
            // Initialize all statistics
            $played = 0;
            $won = 0;
            $drawn = 0;
            $lost = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;
            $yellowCards = 0;
            $redCards = 0;
            $cleanSheets = 0;

            // Home/Away breakdown
            $homePlayed = 0;
            $homeWon = 0;
            $homeDrawn = 0;
            $homeLost = 0;
            $homeGoalsFor = 0;
            $homeGoalsAgainst = 0;

            $awayPlayed = 0;
            $awayWon = 0;
            $awayDrawn = 0;
            $awayLost = 0;
            $awayGoalsFor = 0;
            $awayGoalsAgainst = 0;

            // Form (last 5 matches)
            $form = [];

            // Get all completed matches for this team, ordered by date (most recent last)
            $homeMatches = $this->matches()
                ->where('home_team_id', $team->id)
                ->where('status', 'completed')
                ->orderBy('kickoff_time', 'asc')
                ->get();

            $awayMatches = $this->matches()
                ->where('away_team_id', $team->id)
                ->where('status', 'completed')
                ->orderBy('kickoff_time', 'asc')
                ->get();

            // Process home matches
            foreach ($homeMatches as $match) {
                $homePlayed++;
                $played++;

                $homeGoalsFor += $match->home_score;
                $homeGoalsAgainst += $match->away_score;
                $goalsFor += $match->home_score;
                $goalsAgainst += $match->away_score;

                // Cards
                $yellowCards += $match->home_yellow_cards ?? 0;
                $redCards += $match->home_red_cards ?? 0;

                // Clean sheet (conceded 0)
                if ($match->away_score == 0) {
                    $cleanSheets++;
                }

                // Result
                if ($match->home_score > $match->away_score) {
                    $won++;
                    $homeWon++;
                    $form[] = 'W';
                } elseif ($match->home_score == $match->away_score) {
                    $drawn++;
                    $homeDrawn++;
                    $form[] = 'D';
                } else {
                    $lost++;
                    $homeLost++;
                    $form[] = 'L';
                }
            }

            // Process away matches
            foreach ($awayMatches as $match) {
                $awayPlayed++;
                $played++;

                $awayGoalsFor += $match->away_score;
                $awayGoalsAgainst += $match->home_score;
                $goalsFor += $match->away_score;
                $goalsAgainst += $match->home_score;

                // Cards
                $yellowCards += $match->away_yellow_cards ?? 0;
                $redCards += $match->away_red_cards ?? 0;

                // Clean sheet (conceded 0)
                if ($match->home_score == 0) {
                    $cleanSheets++;
                }

                // Result
                if ($match->away_score > $match->home_score) {
                    $won++;
                    $awayWon++;
                    $form[] = 'W';
                } elseif ($match->away_score == $match->home_score) {
                    $drawn++;
                    $awayDrawn++;
                    $form[] = 'D';
                } else {
                    $lost++;
                    $awayLost++;
                    $form[] = 'L';
                }
            }

            // Calculate points
            $points = ($won * $winPoints) + ($drawn * $drawPoints) + ($lost * $lossPoints);
            $goalDifference = $goalsFor - $goalsAgainst;

            // Get last 5 results
            $formArray = array_slice($form, -5);

            $standingsData[] = [
                'team_id' => $team->id,
                'played' => $played,
                'won' => $won,
                'drawn' => $drawn,
                'lost' => $lost,
                'goals_for' => $goalsFor,
                'goals_against' => $goalsAgainst,
                'goal_difference' => $goalDifference,
                'points' => $points,
                // Cards
                'yellow_cards' => $yellowCards,
                'red_cards' => $redCards,
                // Clean sheets
                'clean_sheets' => $cleanSheets,
                // Home breakdown
                'home_played' => $homePlayed,
                'home_won' => $homeWon,
                'home_drawn' => $homeDrawn,
                'home_lost' => $homeLost,
                'home_goals_for' => $homeGoalsFor,
                'home_goals_against' => $homeGoalsAgainst,
                // Away breakdown
                'away_played' => $awayPlayed,
                'away_won' => $awayWon,
                'away_drawn' => $awayDrawn,
                'away_lost' => $awayLost,
                'away_goals_for' => $awayGoalsFor,
                'away_goals_against' => $awayGoalsAgainst,
                // Form
                'form' => $formArray,
            ];
        }

        // Sort by points, then goal difference, then goals scored
        usort($standingsData, function($a, $b) {
            if ($a['points'] != $b['points']) {
                return $b['points'] - $a['points'];
            }
            if ($a['goal_difference'] != $b['goal_difference']) {
                return $b['goal_difference'] - $a['goal_difference'];
            }
            return $b['goals_for'] - $a['goals_for'];
        });

        // Update standings
        foreach ($standingsData as $index => $data) {
            TournamentStanding::updateOrCreate(
                [
                    'tournament_id' => $this->id,
                    'tournament_team_id' => $data['team_id'],
                ],
                [
                    'played' => $data['played'],
                    'won' => $data['won'],
                    'drawn' => $data['drawn'],
                    'lost' => $data['lost'],
                    'goals_for' => $data['goals_for'],
                    'goals_against' => $data['goals_against'],
                    'goal_difference' => $data['goal_difference'],
                    'points' => $data['points'],
                    'position' => $index + 1,
                    // Cards
                    'yellow_cards' => $data['yellow_cards'],
                    'red_cards' => $data['red_cards'],
                    // Clean sheets
                    'clean_sheets' => $data['clean_sheets'],
                    // Home breakdown
                    'home_played' => $data['home_played'],
                    'home_won' => $data['home_won'],
                    'home_drawn' => $data['home_drawn'],
                    'home_lost' => $data['home_lost'],
                    'home_goals_for' => $data['home_goals_for'],
                    'home_goals_against' => $data['home_goals_against'],
                    // Away breakdown
                    'away_played' => $data['away_played'],
                    'away_won' => $data['away_won'],
                    'away_drawn' => $data['away_drawn'],
                    'away_lost' => $data['away_lost'],
                    'away_goals_for' => $data['away_goals_for'],
                    'away_goals_against' => $data['away_goals_against'],
                    // Form
                    'form' => json_encode($data['form']),
                ]
            );
        }
    }

    public function lockSquads(): void
    {
        // Lock all squads when tournament starts
        $this->teams()->each(function ($team) {
            $team->squads()->update(['is_locked' => true]);
        });
    }

    public function unlockSquads(): void
    {
        // Allow super admin to override lock
        $this->teams()->each(function ($team) {
            $team->squads()->update(['is_locked' => false]);
        });
    }

    /**
     * Reopen tournament for reshuffling when teams are added/removed after fixtures generated
     * This clears existing matches and standings, and reopens registration
     * Returns array with 'reopened' boolean and 'message' string
     */
    public function reopenForReshuffle(): array
    {
        // Only reopen if tournament is closed (has matches generated but not started)
        if ($this->status !== self::STATUS_CLOSED) {
            return [
                'reopened' => false,
                'message' => 'Tournament is not in closed status'
            ];
        }

        // Check if there are any completed matches - if so, don't allow reshuffling
        $completedMatches = $this->matches()->where('status', 'completed')->count();
        if ($completedMatches > 0) {
            return [
                'reopened' => false,
                'message' => 'Cannot reshuffle - some matches have already been completed'
            ];
        }

        // Delete all matches
        $matchesDeleted = $this->matches()->count();
        $this->matches()->delete();

        // Delete all standings
        $this->standings()->delete();

        // Reopen registration
        $this->update(['status' => self::STATUS_OPEN]);

        // Unlock squads if they were locked
        $this->unlockSquads();

        return [
            'reopened' => true,
            'message' => "Tournament reopened for reshuffling. {$matchesDeleted} matches and standings cleared."
        ];
    }

    /**
     * Check if tournament can be reshuffled (has matches but no completed ones)
     */
    public function canReshuffle(): bool
    {
        if ($this->status !== self::STATUS_CLOSED) {
            return false;
        }

        // Can't reshuffle if any matches are completed
        return $this->matches()->where('status', 'completed')->count() === 0;
    }

    // Competition Format Methods
    public function getCompetitionFormatNameAttribute(): string
    {
        return self::COMPETITION_FORMATS[$this->competition_format]['name'] ?? 'Not Set';
    }

    public function getCompetitionFormatDescriptionAttribute(): string
    {
        return self::COMPETITION_FORMATS[$this->competition_format]['description'] ?? '';
    }

    public function hasStandings(): bool
    {
        return self::COMPETITION_FORMATS[$this->competition_format]['has_standings'] ?? false;
    }

    public function hasGroups(): bool
    {
        return self::COMPETITION_FORMATS[$this->competition_format]['has_groups'] ?? false;
    }

    /**
     * Calculate estimated total matches based on competition format and number of teams
     */
    public function calculateEstimatedMatches(int $numTeams = null): int
    {
        $numTeams = $numTeams ?? $this->max_teams ?? 0;

        if ($numTeams < 2) {
            return 0;
        }

        return match($this->competition_format) {
            self::FORMAT_LEAGUE => $numTeams * ($numTeams - 1) / 2, // Single round league
            self::FORMAT_ROUND_ROBIN => $numTeams * ($numTeams - 1), // Home and away
            self::FORMAT_LEAGUE_CUP => ($numTeams * ($numTeams - 1) / 2) + ($numTeams - 1), // League + knockout
            self::FORMAT_KNOCKOUT => $numTeams - 1, // Single elimination
            self::FORMAT_KNOCKOUT_PLUS => $numTeams, // Knockout + third place
            self::FORMAT_DOUBLE_ELIMINATION => (2 * $numTeams) - 2 + $this->calculateKnockoutMatches($numTeams), // Double elimination
            self::FORMAT_GROUPS_KNOCKOUT => $this->calculateGroupStageMatches($numTeams) + $this->calculateKnockoutMatches($numTeams),
            default => $numTeams * ($numTeams - 1) / 2,
        };
    }

    /**
     * Calculate knockout stage matches (next power of 2)
     */
    private function calculateKnockoutMatches(int $numTeams): int
    {
        $nextPowerOf2 = pow(2, ceil(log($numTeams, 2)));
        return $nextPowerOf2 - 1;
    }

    /**
     * Calculate group stage matches (assuming 4 teams per group)
     */
    private function calculateGroupStageMatches(int $numTeams): int
    {
        $numGroups = ceil($numTeams / 4); // 4 teams per group
        $teamsPerGroup = $numTeams / $numGroups;
        return $numGroups * ($teamsPerGroup * ($teamsPerGroup - 1) / 2);
    }

    /**
     * Get format information for display
     */
    public function getFormatInfo(): array
    {
        return self::COMPETITION_FORMATS[$this->competition_format] ?? [
            'name' => 'Not Set',
            'description' => 'Please select a competition format',
            'matches_per_team' => '-',
            'total_matches' => '-',
            'has_standings' => false,
            'has_groups' => false,
        ];
    }

    /**
     * Get all available competition formats for dropdowns
     */
    public static function getCompetitionFormatsForDropdown(): array
    {
        $formats = [];
        foreach (self::COMPETITION_FORMATS as $key => $format) {
            $formats[$key] = $format['name'] . ' - ' . $format['description'];
        }
        return $formats;
    }

    // Custom Format Builder Methods

    /**
     * Check if tournament has custom format
     */
    public function hasCustomFormat(): bool
    {
        return !empty($this->custom_format_config);
    }

    /**
     * Get custom format config
     */
    public function getCustomFormatConfig(): ?array
    {
        return $this->custom_format_config;
    }

    /**
     * Get custom format name
     */
    public function getCustomFormatName(): ?string
    {
        return $this->custom_format_name;
    }

    /**
     * Get hybrid stage config
     */
    public function getHybridConfig(): ?array
    {
        return $this->hybrid_stage_config;
    }

    /**
     * Check if tournament is hybrid format (groups + knockout)
     */
    public function isHybridFormat(): bool
    {
        return $this->competition_format === self::FORMAT_GROUPS_KNOCKOUT ||
               !empty($this->hybrid_stage_config);
    }

    /**
     * Get teams advancing from each group
     */
    public function getTeamsFromGroup(): int
    {
        return $this->teams_from_group ?? 2;
    }

    /**
     * Check if third place match is included
     */
    public function hasThirdPlaceMatch(): bool
    {
        return $this->include_third_place ?? false;
    }

    /**
     * Get all bracket matches (for custom bracket builder)
     */
    public function getBracketMatches()
    {
        return $this->matches()
            ->whereNotNull('bracket_position')
            ->orderBy('round')
            ->orderBy('bracket_position')
            ->get();
    }
}
