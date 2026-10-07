<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Appeal Model
 *
 * Manages appeals against disciplinary decisions
 */
class Appeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'disciplinary_case_id',
        'player_id',
        'organization_id',
        'appeal_number',
        'grounds',
        'evidence',
        'supporting_documents',
        'status',
        'outcome',
        'outcome_reason',
        'original_decision',
        'original_reason',
        'modified_decision',
        'modified_suspension_matches',
        'modified_suspension_days',
        'modified_fine_amount',
        'heard_by',
        'heard_at',
        'decision_date',
        'submitted_by',
        'decided_by',
    ];

    protected $casts = [
        'evidence' => 'array',
        'supporting_documents' => 'array',
        'heard_at' => 'datetime',
        'decision_date' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    // Outcomes
    const OUTCOME_UPHELD = 'upheld';
    const OUTCOME_DISMISSED = 'dismissed';
    const OUTCOME_MODIFIED = 'modified';

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_WITHDRAWN => 'Withdrawn',
        ];
    }

    /**
     * Get outcome options
     */
    public static function getOutcomes(): array
    {
        return [
            self::OUTCOME_UPHELD => 'Appeal Upheld',
            self::OUTCOME_DISMISSED => 'Appeal Dismissed',
            self::OUTCOME_MODIFIED => 'Decision Modified',
        ];
    }

    // Relationships
    public function disciplinaryCase(): BelongsTo
    {
        return $this->belongsTo(DisciplinaryCase::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function heardBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'heard_by');
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
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
        return $query->whereIn('status', [self::STATUS_ACCEPTED, self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
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
        return in_array($this->status, [self::STATUS_ACCEPTED, self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }

    public function canWithdraw(): bool
    {
        return $this->isPending();
    }

    /**
     * Generate appeal number
     */
    public static function generateAppealNumber(): string
    {
        $prefix = 'APL';
        $year = now()->year;
        $random = strtoupper(Str::random(4));
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Submit appeal
     */
    public static function submit(
        int $caseId,
        int $playerId,
        int $organizationId,
        string $grounds,
        array $evidence = [],
        int $submittedBy
    ): self {
        $case = DisciplinaryCase::findOrFail($caseId);

        return self::create([
            'disciplinary_case_id' => $caseId,
            'player_id' => $playerId,
            'organization_id' => $organizationId,
            'appeal_number' => self::generateAppealNumber(),
            'grounds' => $grounds,
            'evidence' => $evidence,
            'status' => self::STATUS_PENDING,
            'original_decision' => $case->decision,
            'original_reason' => $case->decision_reason,
            'submitted_by' => $submittedBy,
        ]);
    }

    /**
     * Start review
     */
    public function startReview(User $user): void
    {
        $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'heard_by' => $user->id,
            'heard_at' => now(),
        ]);
    }

    /**
     * Accept appeal (uphold)
     */
    public function accept(string $reason, User $user): void
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'outcome' => self::OUTCOME_UPHELD,
            'outcome_reason' => $reason,
            'decided_by' => $user->id,
            'decision_date' => now()->toDateString(),
        ]);

        // Clear the original disciplinary decision
        $this->disciplinaryCase->update([
            'decision' => 'cleared',
            'decision_reason' => 'Appeal upheld: ' . $reason,
        ]);

        // End any active suspension
        $suspension = $this->disciplinaryCase->suspension;
        if ($suspension && $suspension->isActive()) {
            $suspension->markAsServed();
        }
    }

    /**
     * Dismiss appeal
     */
    public function dismiss(string $reason, User $user): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'outcome' => self::OUTCOME_DISMISSED,
            'outcome_reason' => $reason,
            'decided_by' => $user->id,
            'decision_date' => now()->toDateString(),
        ]);
    }

    /**
     * Modify decision
     */
    public function modify(
        string $newDecision,
        ?int $newMatches = null,
        ?int $newDays = null,
        ?float $newFine = null,
        string $reason,
        User $user
    ): void {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'outcome' => self::OUTCOME_MODIFIED,
            'outcome_reason' => $reason,
            'modified_decision' => $newDecision,
            'modified_suspension_matches' => $newMatches,
            'modified_suspension_days' => $newDays,
            'modified_fine_amount' => $newFine,
            'decided_by' => $user->id,
            'decision_date' => now()->toDateString(),
        ]);

        // Update the disciplinary case
        $this->disciplinaryCase->update([
            'decision' => $newDecision,
            'suspension_matches' => $newMatches,
            'suspension_days' => $newDays,
            'fine_amount' => $newFine,
            'decision_reason' => 'Modified on appeal: ' . $reason,
        ]);

        // Update suspension
        $suspension = $this->disciplinaryCase->suspension;
        if ($suspension && $suspension->isActive()) {
            $suspension->update([
                'matches_to_serve' => $newMatches ?? $suspension->matches_to_serve,
                'days_to_serve' => $newDays ?? $suspension->days_to_serve,
                'fine_amount' => $newFine ?? $suspension->fine_amount,
            ]);
        }
    }

    /**
     * Withdraw appeal
     */
    public function withdraw(): void
    {
        $this->update([
            'status' => self::STATUS_WITHDRAWN,
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_UNDER_REVIEW => 'info',
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_WITHDRAWN => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get all appeals for a player
     */
    public static function getHistoryForPlayer(int $playerId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_id', $playerId)
            ->with(['disciplinaryCase'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
