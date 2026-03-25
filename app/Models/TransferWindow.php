<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * TransferWindow Model
 *
 * Manages transfer windows for leagues and tournaments
 */
class TransferWindow extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'tournament_id',
        'name',
        'window_type',
        'start_date',
        'end_date',
        'is_active',
        'allow_loan',
        'allow_emergency',
        'rules',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'allow_loan' => 'boolean',
        'allow_emergency' => 'boolean',
    ];

    // Window type constants
    const TYPE_TRANSFER = 'transfer';
    const TYPE_LOAN = 'loan';
    const TYPE_EMERGENCY = 'emergency';
    const TYPE_COMBINED = 'combined';

    /**
     * Get window type options
     */
    public static function getWindowTypes(): array
    {
        return [
            self::TYPE_TRANSFER => 'Transfer Window',
            self::TYPE_LOAN => 'Loan Window',
            self::TYPE_EMERGENCY => 'Emergency Window',
            self::TYPE_COMBINED => 'Transfer & Loan Window',
        ];
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(PlayerTransfer::class, 'transfer_window_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '>', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
    }

    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    // Helper methods
    public function isOpen(): bool
    {
        $today = now()->toDateString();
        return $this->is_active
            && $this->start_date <= $today
            && $this->end_date >= $today;
    }

    public function isClosed(): bool
    {
        return !$this->isOpen();
    }

    public function isUpcoming(): bool
    {
        return $this->is_active && $this->start_date > now()->toDateString();
    }

    public function hasEnded(): bool
    {
        return $this->end_date < now()->toDateString();
    }

    public function daysRemaining(): ?int
    {
        if ($this->hasEnded()) {
            return 0;
        }
        if ($this->isUpcoming()) {
            return now()->diffInDays($this->start_date, false);
        }
        return now()->diffInDays($this->end_date, false);
    }

    public function canAcceptLoans(): bool
    {
        return $this->allow_loan && in_array($this->window_type, [self::TYPE_LOAN, self::TYPE_COMBINED]);
    }

    public function canAcceptEmergency(): bool
    {
        return $this->allow_emergency && $this->window_type === self::TYPE_EMERGENCY;
    }

    public function getStatusBadgeClass(): string
    {
        if ($this->hasEnded()) {
            return 'secondary';
        }
        if ($this->isUpcoming()) {
            return 'info';
        }
        if ($this->isOpen()) {
            return 'success';
        }
        return 'warning';
    }

    public function getStatusLabel(): string
    {
        if ($this->hasEnded()) {
            return 'Closed';
        }
        if ($this->isUpcoming()) {
            return 'Upcoming';
        }
        if ($this->isOpen()) {
            return 'Open';
        }
        return 'Inactive';
    }

    /**
     * Get all pending transfers in this window
     */
    public function getPendingTransfers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->transfers()->pending()->get();
    }

    /**
     * Get all completed transfers in this window
     */
    public function getCompletedTransfers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->transfers()->completed()->get();
    }

    /**
     * Get transfer statistics for this window
     */
    public function getTransferStats(): array
    {
        $transfers = $this->transfers();

        return [
            'total' => $transfers->count(),
            'pending' => $transfers->pending()->count(),
            'approved' => $transfers->approved()->count(),
            'rejected' => $transfers->rejected()->count(),
            'completed' => $transfers->completed()->count(),
            'cancelled' => $transfers->cancelled()->count(),
            'total_fee' => $transfers->completed()->sum('transfer_fee'),
        ];
    }

    /**
     * Check if a player can be transferred in this window
     */
    public function canTransferPlayer(Player $player): array
    {
        $errors = [];

        if (!$this->isOpen()) {
            $errors[] = 'Transfer window is not open';
        }

        if (!$this->allow_loan && $this->window_type === self::TYPE_TRANSFER) {
            $errors[] = 'This window does not allow transfers';
        }

        if ($player->hasActiveContract()) {
            // Check contract release clause
            $contract = $player->getActiveContract();
            if ($contract && $contract->release_clause) {
                // Player can be released if release clause is met
            } else {
                $errors[] = 'Player has an active contract without release clause';
            }
        }

        return $errors;
    }

    /**
     * Get current active window for organization
     */
    public static function getCurrentForOrganization(int $organizationId): ?self
    {
        return self::forOrganization($organizationId)->current()->first();
    }

    /**
     * Get upcoming windows for organization
     */
    public static function getUpcomingForOrganization(int $organizationId): \Illuminate\Database\Eloquent\Collection
    {
        return self::forOrganization($organizationId)->upcoming()->orderBy('start_date')->get();
    }
}
