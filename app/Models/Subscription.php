<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'plan_id',
        'name',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'canceled_at',
        'gateway',
        'gateway_subscription_id',
        'gateway_customer_id',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'metadata' => 'array',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_TRIALING = 'trialing';
    const STATUS_PAUSED = 'paused';
    const STATUS_INCOMPLETE = 'incomplete';

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', self::STATUS_CANCELED);
    }

    public function scopeOnTrial($query)
    {
        return $query->where('status', self::STATUS_TRIALING)
                    ->whereNotNull('trial_ends_at')
                    ->where('trial_ends_at', '>', now());
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isOnTrial(): bool
    {
        return $this->status === self::STATUS_TRIALING &&
               $this->trial_ends_at &&
               $this->trial_ends_at->isFuture();
    }

    public function isPastDue(): bool
    {
        return $this->status === self::STATUS_PAST_DUE;
    }

    public function hasEnded(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function daysRemaining(): int
    {
        if (!$this->ends_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->ends_at, false));
    }

    public function daysUntilTrialEnd(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELED,
            'canceled_at' => now(),
        ]);
    }

    public function resume(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'canceled_at' => null,
        ]);
    }

    public function extendTrial(int $days): void
    {
        $currentTrialEnd = $this->trial_ends_at ?? now();

        $this->update([
            'trial_ends_at' => $currentTrialEnd->addDays($days),
        ]);
    }

    public function changePlan(int $newPlanId): void
    {
        $this->update([
            'plan_id' => $newPlanId,
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_CANCELED => 'warning',
            self::STATUS_PAST_DUE => 'danger',
            self::STATUS_TRIALING => 'info',
            self::STATUS_PAUSED => 'secondary',
            self::STATUS_INCOMPLETE => 'warning',
            default => 'secondary'
        };
    }

    public function getFormattedStartDate(): string
    {
        return $this->starts_at ? $this->starts_at->format('M d, Y') : 'N/A';
    }

    public function getFormattedEndDate(): string
    {
        return $this->ends_at ? $this->ends_at->format('M d, Y') : 'Never';
    }

    // Calculate prorated amount for plan change
    public function calculateProratedAmount(int $newPlanPrice): float
    {
        if (!$this->ends_at || !$this->plan) {
            return $newPlanPrice;
        }

        $remainingDays = $this->daysRemaining();
        $totalDays = $this->starts_at->diffInDays($this->ends_at);

        if ($totalDays === 0) {
            return $newPlanPrice;
        }

        $currentPlanDailyRate = $this->plan->price / $totalDays;
        $unusedCredit = $currentPlanDailyRate * $remainingDays;

        return max(0, $newPlanPrice - $unusedCredit);
    }
}
