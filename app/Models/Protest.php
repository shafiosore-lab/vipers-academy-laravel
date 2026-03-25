<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Protest Model
 *
 * Manages match-related protests
 */
class Protest extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_id',
        'organization_id',
        'tournament_id',
        'protest_number',
        'protest_type',
        'description',
        'grounds',
        'evidence',
        'status',
        'outcome',
        'outcome_reason',
        'resolution',
        'resolved_by',
        'resolved_at',
        'submitted_by',
    ];

    protected $casts = [
        'evidence' => 'array',
        'resolved_at' => 'datetime',
    ];

    // Protest types
    const TYPE_REFEREE = 'referee_decision';
    const TYPE_ELIGIBILITY = 'eligibility';
    const TYPE_VENUE = 'venue';
    const TYPE_SCHEDULE = 'schedule';
    const TYPE_OTHER = 'other';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_UPHELD = 'upheld';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    // Resolutions
    const RESOLUTION_MATCH_VOIDED = 'match_voided';
    const RESOLUTION_RESULT_STANDING = 'result_standing';
    const RESOLUTION_REPLAY_ORDERED = 'replay_ordered';
    const RESOLUTION_PROTEST_DISMISSED = 'protest_dismissed';

    /**
     * Get protest type options
     */
    public static function getProtestTypes(): array
    {
        return [
            self::TYPE_REFEREE => 'Referee Decision',
            self::TYPE_ELIGIBILITY => 'Player Eligibility',
            self::TYPE_VENUE => 'Venue Issue',
            self::TYPE_SCHEDULE => 'Schedule Issue',
            self::TYPE_OTHER => 'Other',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_UPHELD => 'Upheld',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_WITHDRAWN => 'Withdrawn',
        ];
    }

    /**
     * Get resolution options
     */
    public static function getResolutions(): array
    {
        return [
            self::RESOLUTION_MATCH_VOIDED => 'Match Voided',
            self::RESOLUTION_RESULT_STANDING => 'Original Result Stands',
            self::RESOLUTION_REPLAY_ORDERED => 'Replay Ordered',
            self::RESOLUTION_PROTEST_DISMISSED => 'Protest Dismissed',
        ];
    }

    // Relationships
    public function match(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'match_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', [self::STATUS_UPHELD, self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }

    public function scopeByMatch($query, $matchId)
    {
        return $query->where('match_id', $matchId);
    }

    public function scopeByTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isResolved(): bool
    {
        return in_array($this->status, [self::STATUS_UPHELD, self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }

    public function canWithdraw(): bool
    {
        return $this->isPending();
    }

    /**
     * Generate protest number
     */
    public static function generateProtestNumber(): string
    {
        $prefix = 'PRT';
        $year = now()->year;
        $random = strtoupper(Str::random(4));
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Submit protest
     */
    public static function submit(
        int $matchId,
        int $teamId,
        int $organizationId,
        int $tournamentId,
        string $type,
        string $description,
        string $grounds,
        array $evidence = [],
        int $submittedBy
    ): self {
        return self::create([
            'match_id' => $matchId,
            'team_id' => $teamId,
            'organization_id' => $organizationId,
            'tournament_id' => $tournamentId,
            'protest_number' => self::generateProtestNumber(),
            'protest_type' => $type,
            'description' => $description,
            'grounds' => $grounds,
            'evidence' => $evidence,
            'status' => self::STATUS_PENDING,
            'submitted_by' => $submittedBy,
        ]);
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
     * Uphold protest
     */
    public function uphold(string $resolution, string $reason, User $user): void
    {
        $this->update([
            'status' => self::STATUS_UPHELD,
            'outcome' => 'upheld',
            'outcome_reason' => $reason,
            'resolution' => $resolution,
            'resolved_by' => $user->id,
            'resolved_at' => now(),
        ]);

        // Handle resolution actions
        $this->handleResolution($resolution);
    }

    /**
     * Reject protest
     */
    public function reject(string $reason, User $user): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'outcome' => 'rejected',
            'outcome_reason' => $reason,
            'resolution' => self::RESOLUTION_PROTEST_DISMISSED,
            'resolved_by' => $user->id,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Withdraw protest
     */
    public function withdraw(): void
    {
        $this->update([
            'status' => self::STATUS_WITHDRAWN,
        ]);
    }

    /**
     * Handle resolution actions
     */
    protected function handleResolution(string $resolution): void
    {
        switch ($resolution) {
            case self::RESOLUTION_MATCH_VOIDED:
                // Void the match
                if ($this->match) {
                    $this->match->update([
                        'status' => 'cancelled',
                        'cancellation_reason' => 'Match voided due to protest',
                    ]);
                }
                break;

            case self::RESOLUTION_REPLAY_ORDERED:
                // Mark for replay - could create a new match
                // Implementation depends on business logic
                break;

            case self::RESOLUTION_RESULT_STANDING:
                // No action needed - original result stands
                break;
        }
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_UNDER_REVIEW => 'info',
            self::STATUS_UPHELD => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_WITHDRAWN => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get all protests for a tournament
     */
    public static function getForTournament(int $tournamentId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('tournament_id', $tournamentId)
            ->with(['match', 'team'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending protests for organization
     */
    public static function getPendingForOrganization(int $organizationId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('organization_id', $organizationId)
            ->pending()
            ->with(['match', 'team', 'tournament'])
            ->get();
    }
}
