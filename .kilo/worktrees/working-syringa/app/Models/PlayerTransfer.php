<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * PlayerTransfer Model
 *
 * Manages player transfers between teams
 */
class PlayerTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'transfer_window_id',
        'from_team_id',
        'to_team_id',
        'transfer_type',
        'transfer_fee',
        'fee_type',
        'financial_notes',
        'contract_duration_months',
        'salary',
        'signing_bonus',
        'status',
        'rejection_reason',
        'requested_date',
        'effective_date',
        'expiry_date',
        'requested_by',
        'approved_by',
        'approved_at',
        'documents',
        'notes',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
        'transfer_fee' => 'decimal:2',
        'salary' => 'decimal:2',
        'signing_bonus' => 'decimal:2',
        'documents' => 'array',
    ];

    // Transfer type constants
    const TYPE_PERMANENT = 'permanent';
    const TYPE_LOAN = 'loan';
    const TYPE_FREE = 'free';

    // Fee type constants
    const FEE_FIXED = 'fixed';
    const FEE_NEGOTIABLE = 'negotiable';
    const FEE_FREE = 'free';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get transfer type options
     */
    public static function getTransferTypes(): array
    {
        return [
            self::TYPE_PERMANENT => 'Permanent Transfer',
            self::TYPE_LOAN => 'Loan',
            self::TYPE_FREE => 'Free Transfer',
        ];
    }

    /**
     * Get fee type options
     */
    public static function getFeeTypes(): array
    {
        return [
            self::FEE_FIXED => 'Fixed Fee',
            self::FEE_NEGOTIABLE => 'Negotiable',
            self::FEE_FREE => 'Free',
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
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    // Relationships
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function transferWindow(): BelongsTo
    {
        return $this->belongsTo(TransferWindow::class, 'transfer_window_id');
    }

    public function fromTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'from_team_id');
    }

    public function toTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'to_team_id');
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

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByWindow($query, $windowId)
    {
        return $query->where('transfer_window_id', $windowId);
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

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED ||
               ($this->expiry_date && $this->expiry_date < now()->toDateString() && !$this->isCompleted());
    }

    public function canApprove(): bool
    {
        return $this->isPending() && !$this->isExpired();
    }

    public function canReject(): bool
    {
        return $this->isPending();
    }

    public function canCancel(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    public function canComplete(): bool
    {
        return $this->isApproved() && !$this->isExpired();
    }

    /**
     * Approve the transfer
     */
    public function approve(int $userId): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the transfer
     */
    public function reject(int $userId, string $reason): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $userId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Cancel the transfer
     */
    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Complete the transfer
     */
    public function complete(int $effectiveDate = null): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'effective_date' => $effectiveDate ?? now()->toDateString(),
        ]);

        // Update player's current team
        $this->player->update([
            'current_team_id' => $this->to_team_id,
            'joined_date' => $this->effective_date,
        ]);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_EXPIRED => 'light',
            default => 'secondary'
        };
    }

    public function getTransferTypeLabel(): string
    {
        return self::getTransferTypes()[$this->transfer_type] ?? $this->transfer_type;
    }

    public function getFeeDisplay(): string
    {
        if ($this->transfer_type === self::TYPE_FREE) {
            return 'Free';
        }

        return match($this->fee_type) {
            self::FEE_FREE => 'Free',
            self::FEE_FIXED => number_format($this->transfer_fee, 2),
            self::FEE_NEGOTIABLE => number_format($this->transfer_fee, 2) . ' (Negotiable)',
            default => number_format($this->transfer_fee ?? 0, 2),
        };
    }

    /**
     * Get transfer history for a player
     */
    public static function getHistoryForPlayer(int $playerId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('player_id', $playerId)
            ->with(['fromTeam', 'toTeam', 'transferWindow'])
            ->orderBy('requested_date', 'desc')
            ->get();
    }

    /**
     * Get pending transfers for a team (as destination)
     */
    public static function getPendingForTeam(int $teamId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('to_team_id', $teamId)
            ->pending()
            ->with(['player', 'fromTeam', 'transferWindow'])
            ->get();
    }

    /**
     * Get pending transfers from a team (as source)
     */
    public static function getPendingFromTeam(int $teamId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('from_team_id', $teamId)
            ->pending()
            ->with(['player', 'toTeam', 'transferWindow'])
            ->get();
    }
}
