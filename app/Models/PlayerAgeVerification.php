<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * PlayerAgeVerification Model
 *
 * Tracks age verification history for players
 */
class PlayerAgeVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'organization_id',
        'verification_date',
        'verification_method',
        'document_type',
        'document_number',
        'assessed_date_of_birth',
        'assessed_age',
        'declared_age',
        'age_difference',
        'status',
        'findings',
        'notes',
        'medical_assessment',
        'biometric_data',
        'requires_follow_up',
        'follow_up_date',
        'investigated_by',
    ];

    protected $casts = [
        'verification_date' => 'date',
        'assessed_date_of_birth' => 'date',
        'follow_up_date' => 'date',
        'medical_assessment' => 'array',
        'biometric_data' => 'array',
        'requires_follow_up' => 'boolean',
    ];

    // Verification methods
    const METHOD_BIRTH_CERTIFICATE = 'birth_certificate';
    const METHOD_NATIONAL_ID = 'national_id';
    const METHOD_PASSPORT = 'passport';
    const METHOD_MEDICAL = 'medical';
    const METHOD_BIOMETRIC = 'biometric';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_FLAGGED = 'flagged';
    const STATUS_SUSPECTED_FRAUD = 'suspected_fraud';
    const STATUS_CLEARED = 'cleared';

    /**
     * Get verification method options
     */
    public static function getVerificationMethods(): array
    {
        return [
            self::METHOD_BIRTH_CERTIFICATE => 'Birth Certificate',
            self::METHOD_NATIONAL_ID => 'National ID',
            self::METHOD_PASSPORT => 'Passport',
            self::METHOD_MEDICAL => 'Medical Assessment',
            self::METHOD_BIOMETRIC => 'Biometric',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_VERIFIED => 'Verified',
            self::STATUS_FLAGGED => 'Flagged',
            self::STATUS_SUSPECTED_FRAUD => 'Suspected Fraud',
            self::STATUS_CLEARED => 'Cleared',
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

    public function investigator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investigated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopeFlagged($query)
    {
        return $query->whereIn('status', [self::STATUS_FLAGGED, self::STATUS_SUSPECTED_FRAUD]);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    // Helper methods
    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isFlagged(): bool
    {
        return in_array($this->status, [self::STATUS_FLAGGED, self::STATUS_SUSPECTED_FRAUD]);
    }

    public function hasAgeDiscrepancy(): bool
    {
        return $this->age_difference !== null && $this->age_difference != 0;
    }

    public function getDiscrepancyDisplay(): string
    {
        if (!$this->hasAgeDiscrepancy()) {
            return 'No discrepancy';
        }

        $diff = abs($this->age_difference);
        $direction = $this->age_difference > 0 ? 'older' : 'younger';

        return "{$diff} year(s) {$direction} than declared";
    }

    public function markAsVerified(int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_VERIFIED,
            'investigated_by' => $userId ?? auth()->id(),
        ]);

        // Update player status
        $this->player->update([
            'age_verification_status' => 'verified',
            'last_age_verification_date' => now()->toDateString(),
            'age_discrepancy_count' => 0,
        ]);
    }

    public function flag(string $findings, int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_FLAGGED,
            'findings' => $findings,
            'investigated_by' => $userId ?? auth()->id(),
        ]);

        // Update player status
        $this->player->increment('age_discrepancy_count');
        $this->player->update([
            'age_verification_status' => 'flagged',
            'last_age_verification_date' => now()->toDateString(),
        ]);
    }

    public function markAsSuspectedFraud(string $findings, int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_SUSPECTED_FRAUD,
            'findings' => $findings,
            'investigated_by' => $userId ?? auth()->id(),
        ]);

        // Update player status
        $this->player->increment('age_discrepancy_count');
        $this->player->update([
            'age_verification_status' => 'suspected_fraud',
            'last_age_verification_date' => now()->toDateString(),
        ]);
    }

    public function clear(int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_CLEARED,
            'findings' => 'Cleared after review',
            'investigated_by' => $userId ?? auth()->id(),
        ]);

        // Update player status
        $this->player->update([
            'age_verification_status' => 'cleared',
            'last_age_verification_date' => now()->toDateString(),
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_VERIFIED => 'success',
            self::STATUS_FLAGGED => 'info',
            self::STATUS_SUSPECTED_FRAUD => 'danger',
            self::STATUS_CLEARED => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get latest verification for a player
     */
    public static function getLatestForPlayer(int $playerId): ?self
    {
        return self::where('player_id', $playerId)
            ->latest('verification_date')
            ->first();
    }

    /**
     * Get players with age alerts for a category
     */
    public static function getAgeAlertsForCategory(int $organizationId, string $category): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('organization_id', $organizationId)
            ->whereHas('player', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_FLAGGED])
            ->get();
    }
}
