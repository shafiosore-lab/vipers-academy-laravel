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

    public function calculateStandings(): void
    {
        $teams = $this->teams()->where('approval_status', 'approved')->get();

        $standingsData = [];

        foreach ($teams as $team) {
            $played = 0;
            $won = 0;
            $drawn = 0;
            $lost = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;

            // Get all matches for this team
            $homeMatches = $this->matches()->where('home_team_id', $team->id)->where('status', 'completed')->get();
            $awayMatches = $this->matches()->where('away_team_id', $team->id)->where('status', 'completed')->get();

            foreach ($homeMatches as $match) {
                $played++;
                $goalsFor += $match->home_score;
                $goalsAgainst += $match->away_score;

                if ($match->home_score > $match->away_score) {
                    $won++;
                } elseif ($match->home_score == $match->away_score) {
                    $drawn++;
                } else {
                    $lost++;
                }
            }

            foreach ($awayMatches as $match) {
                $played++;
                $goalsFor += $match->away_score;
                $goalsAgainst += $match->home_score;

                if ($match->away_score > $match->home_score) {
                    $won++;
                } elseif ($match->away_score == $match->home_score) {
                    $drawn++;
                } else {
                    $lost++;
                }
            }

            $points = ($won * 3) + $drawn;
            $goalDifference = $goalsFor - $goalsAgainst;

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
