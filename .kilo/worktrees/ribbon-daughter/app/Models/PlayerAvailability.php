<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * PlayerAvailability Model
 *
 * Tracks player availability for matches and training
 */
class PlayerAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'organization_id',
        'availability_date',
        'status',
        'reason',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'availability_date' => 'date',
    ];

    // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';
    const STATUS_TENTATIVE = 'tentative';
    const STATUS_INJURY = 'injury';

    // Reason constants
    const REASON_MATCH = 'match';
    const REASON_TRAINING = 'training';
    const REASON_PERSONAL = 'personal';
    const REASON_INJURY = 'injury';
    const REASON_SUSPENSION = 'suspension';
    const REASON_ILLNESS = 'illness';
    const REASON_WORK = 'work';
    const REASON_OTHER = 'other';

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_UNAVAILABLE => 'Unavailable',
            self::STATUS_TENTATIVE => 'Tentative',
            self::STATUS_INJURY => 'Injured',
        ];
    }

    /**
     * Get reason options
     */
    public static function getReasons(): array
    {
        return [
            self::REASON_MATCH => 'Match Day',
            self::REASON_TRAINING => 'Training',
            self::REASON_PERSONAL => 'Personal Reasons',
            self::REASON_INJURY => 'Injury',
            self::REASON_SUSPENSION => 'Suspension',
            self::REASON_ILLNESS => 'Illness',
            self::REASON_WORK => 'Work Commitment',
            self::REASON_OTHER => 'Other',
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

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', self::STATUS_UNAVAILABLE);
    }

    public function scopeTentative($query)
    {
        return $query->where('status', self::STATUS_TENTATIVE);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('availability_date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('availability_date', [$startDate, $endDate]);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    // Helper methods
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isUnavailable(): bool
    {
        return $this->status === self::STATUS_UNAVAILABLE;
    }

    public function isTentative(): bool
    {
        return $this->status === self::STATUS_TENTATIVE;
    }

    public function isOnInjury(): bool
    {
        return $this->status === self::STATUS_INJURY;
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_UNAVAILABLE => 'danger',
            self::STATUS_TENTATIVE => 'warning',
            self::STATUS_INJURY => 'info',
            default => 'secondary'
        };
    }

    public function getReasonLabel(): string
    {
        return self::getReasons()[$this->reason] ?? 'Not specified';
    }

    /**
     * Mark player as available
     */
    public function markAsAvailable(string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_AVAILABLE,
            'reason' => null,
            'notes' => $notes,
        ]);
    }

    /**
     * Mark player as unavailable
     */
    public function markAsUnavailable(string $reason, string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_UNAVAILABLE,
            'reason' => $reason,
            'notes' => $notes,
        ]);
    }

    /**
     * Mark player as tentative
     */
    public function markAsTentative(string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_TENTATIVE,
            'notes' => $notes,
        ]);
    }

    /**
     * Get availability for a player on a specific date
     */
    public static function getForPlayerOnDate(int $playerId, $date): ?self
    {
        return self::where('player_id', $playerId)
            ->whereDate('availability_date', $date)
            ->first();
    }

    /**
     * Get availability status for a player on a specific date (quick check)
     */
    public static function getStatusForPlayerOnDate(int $playerId, $date): string
    {
        $availability = self::getForPlayerOnDate($playerId, $date);
        return $availability ? $availability->status : self::STATUS_AVAILABLE;
    }

    /**
     * Get upcoming availability for a player
     */
    public static function getUpcomingForPlayer(int $playerId, int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_id', $playerId)
            ->whereDate('availability_date', '>=', now())
            ->whereDate('availability_date', '<=', now()->addDays($days))
            ->orderBy('availability_date')
            ->get();
    }

    /**
     * Bulk set availability for multiple players
     */
    public static function bulkSetAvailability(
        array $playerIds,
        string $status,
        $date,
        ?string $reason = null,
        ?string $notes = null,
        ?int $recordedBy = null
    ): int
    {
        $count = 0;
        $date = Carbon::parse($date)->toDateString();

        foreach ($playerIds as $playerId) {
            self::updateOrCreate(
                [
                    'player_id' => $playerId,
                    'availability_date' => $date,
                ],
                [
                    'organization_id' => auth()->user()?->organization_id,
                    'status' => $status,
                    'reason' => $reason,
                    'notes' => $notes,
                    'recorded_by' => $recordedBy ?? auth()->id(),
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Get availability summary for a date range
     */
    public static function getSummaryForDateRange(int $organizationId, $startDate, $endDate): array
    {
        $records = self::where('organization_id', $organizationId)
            ->whereBetween('availability_date', [$startDate, $endDate])
            ->get();

        return [
            'total' => $records->count(),
            'available' => $records->where('status', self::STATUS_AVAILABLE)->count(),
            'unavailable' => $records->where('status', self::STATUS_UNAVAILABLE)->count(),
            'tentative' => $records->where('status', self::STATUS_TENTATIVE)->count(),
            'injury' => $records->where('status', self::STATUS_INJURY)->count(),
        ];
    }
}
