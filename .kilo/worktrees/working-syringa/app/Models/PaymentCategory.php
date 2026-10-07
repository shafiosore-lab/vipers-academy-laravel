<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_amount',
        'joining_fee',
        'payment_interval_days',
        'grace_period_days',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
        'joining_fee' => 'decimal:2',
        'payment_interval_days' => 'integer',
        'grace_period_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all payments in this category
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_category_id');
    }

    /**
     * Get all players in this category
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'player_payment_category')
            ->withPivot('effective_from', 'effective_to')
            ->withTimestamps();
    }

    /**
     * Get active players currently in this category
     */
    public function activePlayers(): BelongsToMany
    {
        return $this->players()->wherePivotNull('effective_to');
    }

    /**
     * Check if category is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Calculate next due date from a given start date
     */
    public function getNextDueDate(?\Carbon\Carbon $fromDate = null): \Carbon\Carbon
    {
        $from = $fromDate ?? now();
        return $from->addDays($this->payment_interval_days);
    }

    /**
     * Get the grace period end date
     */
    public function getGracePeriodEndDate(\Carbon\Carbon $dueDate): \Carbon\Carbon
    {
        return $dueDate->addDays($this->grace_period_days);
    }

    /**
     * Check if a payment is overdue
     */
    public function isOverdue(\Carbon\Carbon $dueDate): bool
    {
        return now()->greaterThan($this->getGracePeriodEndDate($dueDate));
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Get formatted monthly amount
     */
    public function getFormattedMonthlyAmount(): string
    {
        return 'KSh ' . number_format($this->monthly_amount, 2);
    }

    /**
     * Get formatted joining fee
     */
    public function getFormattedJoiningFee(): string
    {
        return 'KSh ' . number_format($this->joining_fee, 2);
    }

    /**
     * Get total revenue from this category
     */
    public function getTotalRevenue(): float
    {
        return $this->payments()
            ->where('payment_status', 'completed')
            ->sum('amount');
    }

    /**
     * Get pending payments amount
     */
    public function getPendingAmount(): float
    {
        return $this->payments()
            ->where('payment_status', 'pending')
            ->sum('amount');
    }

    /**
     * Get overdue payments amount
     */
    public function getOverdueAmount(): float
    {
        return $this->payments()
            ->where('payment_status', 'pending')
            ->where('due_date', '<', now())
            ->sum('amount');
    }
}
