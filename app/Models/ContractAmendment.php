<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ContractAmendment Model
 *
 * Tracks contract amendments and modifications
 */
class ContractAmendment extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_contract_id',
        'amendment_type',
        'description',
        'previous_value',
        'new_value',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Amendment type constants
    const TYPE_SALARY_CHANGE = 'salary_change';
    const TYPE_DURATION_CHANGE = 'duration_change';
    const TYPE_CLAUSE_ADDITION = 'clause_addition';
    const TYPE_CLAUSE_REMOVAL = 'clause_removal';
    const TYPE_TERMINATION = 'termination';
    const TYPE_EXTENSION = 'extension';
    const TYPE_OTHER = 'other';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get amendment type options
     */
    public static function getAmendmentTypes(): array
    {
        return [
            self::TYPE_SALARY_CHANGE => 'Salary Change',
            self::TYPE_DURATION_CHANGE => 'Duration Change',
            self::TYPE_CLAUSE_ADDITION => 'Add Clause',
            self::TYPE_CLAUSE_REMOVAL => 'Remove Clause',
            self::TYPE_TERMINATION => 'Termination',
            self::TYPE_EXTENSION => 'Contract Extension',
            self::TYPE_OTHER => 'Other',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending Approval',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    // Relationships
    public function playerContract(): BelongsTo
    {
        return $this->belongsTo(PlayerContract::class, 'player_contract_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canApprove(): bool
    {
        return $this->isPending();
    }

    /**
     * Approve the amendment
     */
    public function approve(int $userId): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);

        // Apply the amendment to the contract
        $this->applyToContract();
    }

    /**
     * Apply amendment to the contract
     */
    protected function applyToContract(): void
    {
        $contract = $this->playerContract;

        switch ($this->amendment_type) {
            case self::TYPE_SALARY_CHANGE:
                $contract->update([
                    'base_salary' => $this->new_value,
                ]);
                $contract->calculateTotalValue();
                break;

            case self::TYPE_DURATION_CHANGE:
                $contract->update([
                    'duration_months' => $this->new_value,
                    'end_date' => now()->addMonths($this->new_value),
                ]);
                break;

            case self::TYPE_EXTENSION:
                $contract->update([
                    'end_date' => $this->new_value,
                    'duration_months' => now()->diffInMonths($this->new_value),
                ]);
                break;
        }
    }

    /**
     * Get amendments for a contract
     */
    public static function getForContract(int $contractId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_contract_id', $contractId)
            ->with(['requester', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending amendments count for organization
     */
    public static function getPendingCountForOrganization(int $organizationId): int
    {
        return self::whereHas('playerContract', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->pending()->count();
    }
}
