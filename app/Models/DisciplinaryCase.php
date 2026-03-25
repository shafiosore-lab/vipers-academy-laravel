<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * DisciplinaryCase Model
 *
 * Manages disciplinary cases for players
 */
class DisciplinaryCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'organization_id',
        'team_id',
        'tournament_id',
        'case_number',
        'incident_type',
        'description',
        'incident_date',
        'incident_location',
        'match_id',
        'evidence',
        'witness_statements',
        'card_shown',
        'offense_type',
        'status',
        'decision',
        'suspension_matches',
        'suspension_days',
        'fine_amount',
        'effective_date',
        'end_date',
        'decision_reason',
        'notes',
        'reported_by',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'effective_date' => 'date',
        'end_date' => 'date',
        'decided_at' => 'datetime',
        'evidence' => 'array',
        'witness_statements' => 'array',
        'fine_amount' => 'decimal:2',
    ];

    // Incident types
    const TYPE_VIOLENT_CONDUCT = 'violent_conduct';
    const TYPE_DISSENT = 'dissent';
    const TYPE_ABUSIVE_LANGUAGE = 'abusive_language';
    const TYPE_SPITTING = 'spitting';
    const TYPE_ASSAULT = 'assault';
    const TYPE_DANGEROUS_PLAY = 'dangerous_play';
    const TYPE_UNSPORTSMANLIKE = 'unsportsmanlike';
    const TYPE_RECURSION = 'recursion';
    const TYPE_FAIL_TO_LEAVE = 'fail_to_leave';
    const TYPE_OTHER = 'other';

    // Offense types
    const OFFENSE_MINOR = 'minor';
    const OFFENSE_MODERATE = 'moderate';
    const OFFENSE_SERIOUS = 'serious';
    const OFFENSE_GROSS = 'gross_misconduct';

    // Status constants
    const STATUS_OPEN = 'open';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_PENDING_DECISION = 'pending_decision';
    const STATUS_CLOSED = 'closed';

    // Decisions
    const DECISION_WARNING = 'warning';
    const DECISION_FINE = 'fine';
    const DECISION_SUSPENSION = 'suspension';
    const DECISION_BAN = 'ban';
    const DECISION_CLEARED = 'cleared';

    /**
     * Get incident type options
     */
    public static function getIncidentTypes(): array
    {
        return [
            self::TYPE_VIOLENT_CONDUCT => 'Violent Conduct',
            self::TYPE_DISSENT => 'Dissent',
            self::TYPE_ABUSIVE_LANGUAGE => 'Abusive Language',
            self::TYPE_SPITTING => 'Spitting',
            self::TYPE_ASSAULT => 'Assault',
            self::TYPE_DANGEROUS_PLAY => 'Dangerous Play',
            self::TYPE_UNSPORTSMANLIKE => 'Unsportsmanlike Behavior',
            self::TYPE_RECURSIVE => 'Receiving Second Warning',
            self::TYPE_FAIL_TO_LEAVE => 'Failure to Leave Field',
            self::TYPE_OTHER => 'Other',
        ];
    }

    /**
     * Get offense type options
     */
    public static function getOffenseTypes(): array
    {
        return [
            self::OFFENSE_MINOR => 'Minor Offense',
            self::OFFENSE_MODERATE => 'Moderate Offense',
            self::OFFENSE_SERIOUS => 'Serious Offense',
            self::OFFENSE_GROSS => 'Gross Misconduct',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_PENDING_DECISION => 'Pending Decision',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    /**
     * Get decision options
     */
    public static function getDecisions(): array
    {
        return [
            self::DECISION_WARNING => 'Warning',
            self::DECISION_FINE => 'Fine',
            self::DECISION_SUSPENSION => 'Suspension',
            self::DECISION_BAN => 'Ban',
            self::DECISION_CLEARED => 'Cleared',
        ];
    }

    // Relationships
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'match_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function suspension(): HasOne
    {
        return $this->hasOne(PlayerSuspension::class);
    }

    public function appeal(): HasOne
    {
        return $this->hasOne(Appeal::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    // Helper methods
    public function isOpen(): bool
    {
        return $this->status !== self::STATUS_CLOSED;
    }

    public function hasSuspension(): bool
    {
        return in_array($this->decision, [self::DECISION_SUSPENSION, self::DECISION_BAN]);
    }

    public function hasAppeal(): bool
    {
        return $this->appeal()->exists();
    }

    public function canAppeal(): bool
    {
        return $this->status === self::STATUS_CLOSED && !$this->hasAppeal();
    }

    /**
     * Generate case number
     */
    public static function generateCaseNumber(): string
    {
        $prefix = 'DISC';
        $year = now()->year;
        $random = strtoupper(Str::random(4));
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Start review
     */
    public function startReview(): void
    {
        $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
        ]);
    }

    /**
     * Make decision
     */
    public function makeDecision(
        string $decision,
        ?string $reason,
        int $userId,
        ?int $suspensionMatches = null,
        ?int $suspensionDays = null,
        ?float $fineAmount = null,
        ?string $effectiveDate = null
    ): void {
        $data = [
            'status' => self::STATUS_CLOSED,
            'decision' => $decision,
            'decision_reason' => $reason,
            'decided_by' => $userId,
            'decided_at' => now(),
        ];

        if ($suspensionMatches !== null) {
            $data['suspension_matches'] = $suspensionMatches;
        }

        if ($suspensionDays !== null) {
            $data['suspension_days'] = $suspensionDays;
            $data['end_date'] = now()->addDays($suspensionDays)->toDateString();
        }

        if ($fineAmount !== null) {
            $data['fine_amount'] = $fineAmount;
        }

        if ($effectiveDate !== null) {
            $data['effective_date'] = $effectiveDate;
        } else {
            $data['effective_date'] = now()->toDateString();
        }

        $this->update($data);

        // Create suspension if applicable
        if (in_array($decision, [self::DECISION_SUSPENSION, self::DECISION_BAN])) {
            PlayerSuspension::create([
                'player_id' => $this->player_id,
                'disciplinary_case_id' => $this->id,
                'organization_id' => $this->organization_id,
                'team_id' => $this->team_id,
                'tournament_id' => $this->tournament_id,
                'suspension_type' => $decision === self::DECISION_BAN ? 'permanent_ban' : 'match_ban',
                'matches_to_serve' => $suspensionMatches ?? 0,
                'days_to_serve' => $suspensionDays ?? 0,
                'start_date' => $data['effective_date'],
                'end_date' => $data['end_date'],
                'fine_amount' => $fineAmount,
                'status' => 'active',
                'reason' => $reason,
            ]);
        }
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_OPEN => 'info',
            self::STATUS_UNDER_REVIEW => 'warning',
            self::STATUS_PENDING_DECISION => 'warning',
            self::STATUS_CLOSED => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get all cases for a player
     */
    public static function getHistoryForPlayer(int $playerId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_id', $playerId)
            ->with(['team', 'tournament', 'match'])
            ->orderBy('incident_date', 'desc')
            ->get();
    }

    /**
     * Get active suspensions for organization
     */
    public static function getActiveSuspensions(int $organizationId): \Illuminate\Database\Eloquent\Collection
    {
        return PlayerSuspension::where('organization_id', $organizationId)
            ->active()
            ->with(['player', 'team', 'disciplinaryCase'])
            ->get();
    }
}
