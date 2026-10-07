<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * PlayerSuspension Model
 *
 * Tracks player suspensions and bans
 */
class PlayerSuspension extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'disciplinary_case_id',
        'organization_id',
        'team_id',
        'tournament_id',
        'suspension_type',
        'matches_to_serve',
        'matches_served',
        'days_to_serve',
        'start_date',
        'end_date',
        'fine_amount',
        'status',
        'reason',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];

    // Suspension types
    const TYPE_MATCH_BAN = 'match_ban';
    const TYPE_TEMPORARY = 'temporary_suspension';
    const TYPE_PERMANENT_BAN = 'permanent_ban';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_SERVED = 'served';
    const STATUS_EXPIRED = 'expired';
    const STATUS_BREACHED = 'breached';

    /**
     * Get suspension type options
     */
    public static function getSuspensionTypes(): array
    {
        return [
            self::TYPE_MATCH_BAN => 'Match Ban',
            self::TYPE_TEMPORARY => 'Temporary Suspension',
            self::TYPE_PERMANENT_BAN => 'Permanent Ban',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_SERVED => 'Served',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_BREACHED => 'Breached',
        ];
    }

    // Relationships
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function disciplinaryCase(): BelongsTo
    {
        return $this->belongsTo(DisciplinaryCase::class);
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeServed($query)
    {
        return $query->where('status', self::STATUS_SERVED);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPermanent(): bool
    {
        return $this->suspension_type === self::TYPE_PERMANENT_BAN;
    }

    public function hasServedSuspension(): bool
    {
        if ($this->isPermanent()) {
            return false;
        }

        // Check if all matches are served
        if ($this->matches_to_serve > 0) {
            return $this->matches_served >= $this->matches_to_serve;
        }

        // Check if suspension period has ended
        if ($this->end_date) {
            return Carbon::parse($this->end_date)->isPast();
        }

        return false;
    }

    public function matchesRemaining(): int
    {
        return max(0, $this->matches_to_serve - $this->matches_served);
    }

    public function daysRemaining(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        if (Carbon::parse($this->end_date)->isPast()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->end_date, false);
    }

    public function canPlayMatch(): bool
    {
        if (!$this->isActive()) {
            return true;
        }

        if ($this->isPermanent()) {
            return false;
        }

        // Check match-based suspensions
        if ($this->matches_to_serve > 0) {
            return $this->matches_served >= $this->matches_to_serve;
        }

        // Check time-based suspensions
        if ($this->end_date) {
            return Carbon::parse($this->end_date)->isPast();
        }

        return true;
    }

    /**
     * Record a match served
     */
    public function recordMatchServed(): void
    {
        if ($this->matches_to_serve > 0) {
            $this->increment('matches_served');

            // Check if suspension is now served
            if ($this->matches_served >= $this->matches_to_serve) {
                $this->markAsServed();
            }
        }
    }

    /**
     * Mark suspension as served
     */
    public function markAsServed(): void
    {
        $this->update([
            'status' => self::STATUS_SERVED,
            'end_date' => now()->toDateString(),
        ]);
    }

    /**
     * Mark suspension as expired
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Mark suspension as breached
     */
    public function markAsBreached(string $notes): void
    {
        $this->update([
            'status' => self::STATUS_BREACHED,
            'notes' => $notes,
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'warning',
            self::STATUS_SERVED => 'success',
            self::STATUS_EXPIRED => 'secondary',
            self::STATUS_BREACHED => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get active suspensions for a player
     */
    public static function getActiveForPlayer(int $playerId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_id', $playerId)
            ->active()
            ->get();
    }

    /**
     * Check if player is eligible to play
     */
    public static function isEligible(int $playerId, ?int $tournamentId = null): bool
    {
        $query = self::where('player_id', $playerId)->active();

        if ($tournamentId) {
            $query->where(function ($q) use ($tournamentId) {
                $q->where('tournament_id', $tournamentId)
                  ->orWhereNull('tournament_id');
            });
        }

        $suspensions = $query->get();

        foreach ($suspensions as $suspension) {
            if (!$suspension->canPlayMatch()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all suspensions expiring soon
     */
    public static function getExpiringSoon(int $organizationId, int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        $futureDate = now()->addDays($days)->toDateString();

        return self::where('organization_id', $organizationId)
            ->active()
            ->whereNotNull('end_date')
            ->where('end_date', '<=', $futureDate)
            ->with(['player', 'team'])
            ->get();
    }
}
