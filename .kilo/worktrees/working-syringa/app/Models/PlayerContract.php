<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * PlayerContract Model
 *
 * Manages digital player contracts with signature tracking
 */
class PlayerContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'organization_id',
        'team_id',
        'contract_type',
        'contract_number',
        'start_date',
        'end_date',
        'duration_months',
        'base_salary',
        'signing_bonus',
        'release_clause',
        'payment_frequency',
        'salary_breaks',
        'performance_bonuses',
        'total_contract_value',
        'status',
        'termination_reason',
        'termination_date',
        'is_digitally_signed',
        'signed_at',
        'signature_token',
        'signatures',
        'special_clauses',
        'notes',
        'document_path',
        'attachments',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'termination_date' => 'date',
        'approved_at' => 'datetime',
        'signed_at' => 'datetime',
        'is_digitally_signed' => 'boolean',
        'base_salary' => 'decimal:2',
        'signing_bonus' => 'decimal:2',
        'release_clause' => 'decimal:2',
        'total_contract_value' => 'decimal:2',
        'salary_breaks' => 'array',
        'performance_bonuses' => 'array',
        'signatures' => 'array',
        'attachments' => 'array',
    ];

    // Contract type constants
    const TYPE_PERMANENT = 'permanent';
    const TYPE_LOAN = 'loan';
    const TYPE_YOUTH = 'youth';
    const TYPE_AMATEUR = 'amateur';
    const TYPE_PROFESSIONAL = 'professional';

    // Payment frequency constants
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_MONTHLY = 'monthly';
    const FREQUENCY_ANNUALLY = 'annually';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_SIGNATURE = 'pending_signature';
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_TERMINATED = 'terminated';
    const STATUS_RENEWED = 'renewed';

    /**
     * Get contract type options
     */
    public static function getContractTypes(): array
    {
        return [
            self::TYPE_PERMANENT => 'Permanent Contract',
            self::TYPE_LOAN => 'Loan Contract',
            self::TYPE_YOUTH => 'Youth Contract',
            self::TYPE_AMATEUR => 'Amateur Contract',
            self::TYPE_PROFESSIONAL => 'Professional Contract',
        ];
    }

    /**
     * Get payment frequency options
     */
    public static function getPaymentFrequencies(): array
    {
        return [
            self::FREQUENCY_WEEKLY => 'Weekly',
            self::FREQUENCY_MONTHLY => 'Monthly',
            self::FREQUENCY_ANNUALLY => 'Annually',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_SIGNATURE => 'Pending Signature',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_TERMINATED => 'Terminated',
            self::STATUS_RENEWED => 'Renewed',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(ContractRenewal::class, 'previous_contract_id');
    }

    public function amendments(): HasMany
    {
        return $this->hasMany(ContractAmendment::class, 'player_contract_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING_SIGNATURE);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        $futureDate = now()->addDays($days)->toDateString();
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('end_date', '<=', $futureDate)
            ->where('end_date', '>=', now()->toDateString());
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING_SIGNATURE;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED ||
               ($this->end_date && $this->end_date < now()->toDateString() && !$this->isActive());
    }

    public function isTerminated(): bool
    {
        return $this->status === self::STATUS_TERMINATED;
    }

    public function isSigned(): bool
    {
        return $this->is_digitally_signed && $this->signed_at;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->isActive()
            && $this->end_date
            && $this->end_date <= now()->addDays($days)
            && $this->end_date >= now();
    }

    public function daysRemaining(): ?int
    {
        if (!$this->end_date || !$this->isActive()) {
            return null;
        }

        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->end_date, false);
    }

    public function canApprove(): bool
    {
        return $this->isDraft() || $this->isPending();
    }

    public function canSign(): bool
    {
        return $this->isPending();
    }

    public function canTerminate(): bool
    {
        return $this->isActive();
    }

    public function canRenew(): bool
    {
        return $this->isActive() || $this->isExpired();
    }

    /**
     * Generate unique contract number
     */
    public static function generateContractNumber(): string
    {
        $prefix = 'CON';
        $year = now()->year;
        $random = strtoupper(Str::random(6));
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Submit for signature
     */
    public function submitForSignature(): void
    {
        $this->update([
            'status' => self::STATUS_PENDING_SIGNATURE,
        ]);
    }

    /**
     * Sign the contract digitally
     */
    public function sign(array $signatureData = []): void
    {
        $this->update([
            'is_digitally_signed' => true,
            'signed_at' => now(),
            'signature_token' => Str::random(64),
            'signatures' => array_merge($this->signatures ?? [], [
                [
                    'signed_at' => now()->toIso8601String(),
                    'data' => $signatureData,
                    'ip_address' => request()->ip(),
                ]
            ]),
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Approve the contract
     */
    public function approve(int $userId): void
    {
        $this->update([
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Terminate the contract
     */
    public function terminate(int $userId, string $reason): void
    {
        $this->update([
            'status' => self::STATUS_TERMINATED,
            'termination_reason' => $reason,
            'termination_date' => now()->toDateString(),
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark as expired (auto-called)
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Calculate total contract value
     */
    public function calculateTotalValue(): void
    {
        $salaryTotal = 0;

        // Calculate based on payment frequency
        $monthlySalary = match($this->payment_frequency) {
            self::FREQUENCY_WEEKLY => $this->base_salary * 4.33,
            self::FREQUENCY_ANNUALLY => $this->base_salary / 12,
            default => $this->base_salary,
        };

        $salaryTotal = $monthlySalary * $this->duration_months;

        $this->update([
            'total_contract_value' => $salaryTotal + ($this->signing_bonus ?? 0),
        ]);
    }

    /**
     * Get monthly salary equivalent
     */
    public function getMonthlySalary(): float
    {
        return match($this->payment_frequency) {
            self::FREQUENCY_WEEKLY => $this->base_salary * 4.33,
            self::FREQUENCY_ANNUALLY => $this->base_salary / 12,
            default => $this->base_salary,
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PENDING_SIGNATURE => 'warning',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_EXPIRED => 'light',
            self::STATUS_TERMINATED => 'danger',
            self::STATUS_RENEWED => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get active contract for a player
     */
    public static function getActiveForPlayer(int $playerId): ?self
    {
        return self::where('player_id', $playerId)
            ->where('status', self::STATUS_ACTIVE)
            ->first();
    }

    /**
     * Get all contracts for a player
     */
    public static function getHistoryForPlayer(int $playerId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_id', $playerId)
            ->with(['team', 'organization'])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Get contracts expiring soon for organization
     */
    public static function getExpiringForOrganization(int $organizationId, int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('organization_id', $organizationId)
            ->expiringSoon($days)
            ->with(['player', 'team'])
            ->get();
    }
}
