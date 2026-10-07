<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * PlayerInjury Model
 *
 * Tracks player injuries and recovery progress
 */
class PlayerInjury extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'organization_id',
        'injury_type',
        'body_part',
        'severity',
        'description',
        'injury_date',
        'expected_recovery_date',
        'actual_recovery_date',
        'status',
        'treatment_notes',
        'treating_physician',
        'reported_by',
        'updated_by',
    ];

    protected $casts = [
        'injury_date' => 'date',
        'expected_recovery_date' => 'date',
        'actual_recovery_date' => 'date',
    ];

    // Injury type constants
    const TYPE_MUSCLE = 'muscle';
    const TYPE_LIGAMENT = 'ligament';
    const TYPE_FRACTURE = 'fracture';
    const TYPE_CONCUSSION = 'concussion';
    const TYPE_DISLOCATION = 'dislocation';
    const TYPE_TENDON = 'tendon';
    const TYPE_CONTUSION = 'contusion';
    const TYPE_SPRAIN = 'sprain';
    const TYPE_OTHER = 'other';

    // Body part constants
    const PART_HAMSTRING = 'hamstring';
    const PART_QUAD = 'quadriceps';
    const PART_CALF = 'calf';
    const PART_GROIN = 'groin';
    const PART_KNEE = 'knee';
    const PART_ANKLE = 'ankle';
    const PART_FOOT = 'foot';
    const PART_SHIN = 'shin';
    const PART_HIP = 'hip';
    const PART_BACK = 'back';
    const PART_SHOULDER = 'shoulder';
    const PART_ARM = 'arm';
    const PART_HAND = 'hand';
    const PART_HEAD = 'head';
    const PART_CHEST = 'chest';
    const PART_OTHER = 'other';

    // Severity constants
    const SEVERITY_MILD = 'mild';
    const SEVERITY_MODERATE = 'moderate';
    const SEVERITY_SEVERE = 'severe';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_RECOVERING = 'recovering';
    const STATUS_RECOVERED = 'recovered';
    const STATUS_RECURRENT = 'recurrent';

    /**
     * Get injury type options
     */
    public static function getInjuryTypes(): array
    {
        return [
            self::TYPE_MUSCLE => 'Muscle Strain',
            self::TYPE_LIGAMENT => 'Ligament Injury',
            self::TYPE_FRACTURE => 'Fracture',
            self::TYPE_CONCUSSION => 'Concussion',
            self::TYPE_DISLOCATION => 'Dislocation',
            self::TYPE_TENDON => 'Tendon Injury',
            self::TYPE_CONTUSION => 'Contusion/Bruise',
            self::TYPE_SPRAIN => 'Sprain',
            self::TYPE_OTHER => 'Other',
        ];
    }

    /**
     * Get body part options
     */
    public static function getBodyParts(): array
    {
        return [
            self::PART_HAMSTRING => 'Hamstring',
            self::PART_QUAD => 'Quadriceps',
            self::PART_CALF => 'Calf',
            self::PART_GROIN => 'Groin',
            self::PART_KNEE => 'Knee',
            self::PART_ANKLE => 'Ankle',
            self::PART_FOOT => 'Foot',
            self::PART_SHIN => 'Shin',
            self::PART_HIP => 'Hip',
            self::PART_BACK => 'Back',
            self::PART_SHOULDER => 'Shoulder',
            self::PART_ARM => 'Arm',
            self::PART_HAND => 'Hand',
            self::PART_HEAD => 'Head',
            self::PART_CHEST => 'Chest',
            self::PART_OTHER => 'Other',
        ];
    }

    /**
     * Get severity options
     */
    public static function getSeverities(): array
    {
        return [
            self::SEVERITY_MILD => 'Mild (1-2 weeks)',
            self::SEVERITY_MODERATE => 'Moderate (2-4 weeks)',
            self::SEVERITY_SEVERE => 'Severe (4+ weeks)',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active Injury',
            self::STATUS_RECOVERING => 'Recovering',
            self::STATUS_RECOVERED => 'Recovered',
            self::STATUS_RECURRENT => 'Recurrent',
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

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_RECOVERING]);
    }

    public function scopeRecovered($query)
    {
        return $query->where('status', self::STATUS_RECOVERED);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeDueForRecovery($query)
    {
        return $query->where('status', self::STATUS_RECOVERING)
            ->whereDate('expected_recovery_date', '<=', now());
    }

    // Helper methods
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_RECOVERING]);
    }

    public function isRecovered(): bool
    {
        return $this->status === self::STATUS_RECOVERED;
    }

    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_RECOVERING
            && $this->expected_recovery_date
            && Carbon::parse($this->expected_recovery_date)->isPast();
    }

    public function getDaysSinceInjury(): int
    {
        return Carbon::parse($this->injury_date)->diffInDays(now());
    }

    public function getDaysUntilRecovery(): ?int
    {
        if (!$this->expected_recovery_date) {
            return null;
        }
        return Carbon::now()->diffInDays($this->expected_recovery_date, false);
    }

    public function getRecoveryProgress(): int
    {
        if (!$this->expected_recovery_date) {
            return 0;
        }

        $totalDays = Carbon::parse($this->injury_date)->diffInDays($this->expected_recovery_date);
        $elapsedDays = Carbon::parse($this->injury_date)->diffInDays(now());

        if ($totalDays <= 0) {
            return 0;
        }

        return min(100, round(($elapsedDays / $totalDays) * 100));
    }

    public function markAsRecovered(int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_RECOVERED,
            'actual_recovery_date' => now()->toDateString(),
            'updated_by' => $userId ?? auth()->id(),
        ]);
    }

    public function markAsRecovering(int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_RECOVERING,
            'updated_by' => $userId ?? auth()->id(),
        ]);
    }

    public function markAsRecurrent(int $userId = null): void
    {
        $this->update([
            'status' => self::STATUS_RECURRENT,
            'updated_by' => $userId ?? auth()->id(),
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'danger',
            self::STATUS_RECOVERING => 'warning',
            self::STATUS_RECOVERED => 'success',
            self::STATUS_RECURRENT => 'info',
            default => 'secondary'
        };
    }

    public function getSeverityBadgeClass(): string
    {
        return match($this->severity) {
            self::SEVERITY_MILD => 'success',
            self::SEVERITY_MODERATE => 'warning',
            self::SEVERITY_SEVERE => 'danger',
            default => 'secondary'
        };
    }

    public function getInjuryDescription(): string
    {
        $type = self::getInjuryTypes()[$this->injury_type] ?? $this->injury_type;
        $part = self::getBodyParts()[$this->body_part] ?? $this->body_part;
        return "{$type} - {$part}";
    }
}
