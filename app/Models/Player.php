<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_name',
        'first_name',
        'last_name',
        'category',
        'position',
        'age',
        'jersey_number',
        'image_path',
        'bio',
        'goals',
        'assists',
        'appearances',
        'yellow_cards',
        'red_cards',
        'program_id',
        'registration_status',
        'approval_type',
        'documents_completed',
        'temporary_approval_granted_at',
        'temporary_approval_expires_at',
        'temporary_approval_notes',
        'partner_id',
        'guardian_id',
        'email',
        'parent_guardian_name',
        'parent_phone',
        'training_days',
        'monthly_contribution',
        'status',
        'fee_category' // 'A' for 200, 'B' for 500
    ];

    protected $casts = [
        'temporary_approval_granted_at' => 'datetime',
        'temporary_approval_expires_at' => 'datetime',
    ];

    const POSITION_ORDER = [
        'goalkeeper' => 1,
        'defender' => 2,
        'midfielder' => 3,
        'striker' => 4
    ];

    const CATEGORY_ORDER = [
        'under-13' => 1,
        'under-15' => 2,
        'under-17' => 3,
        'senior' => 4
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function gameStatistics()
    {
        return $this->hasMany(GameStatistic::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function monthlyBillings()
    {
        return $this->hasMany(MonthlyBilling::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function websitePlayer()
    {
        return $this->hasOne(WebsitePlayer::class);
    }

    // ==========================================
    // ATTRIBUTES & ACCESSORS
    // ==========================================

    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        return $this->full_name;
    }

    public function getPositionOrderAttribute()
    {
        return self::POSITION_ORDER[strtolower($this->position)] ?? 999;
    }

    public function getCategoryOrderAttribute()
    {
        return self::CATEGORY_ORDER[strtolower($this->category)] ?? 999;
    }

    public function getFormattedCategoryAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }

    public function getFormattedPositionAttribute()
    {
        return ucfirst($this->position);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('assets/img/players/' . $this->image_path))) {
            return asset('assets/img/players/' . $this->image_path);
        }
        return asset('assets/img/default-player.png');
    }

    // ==========================================
    // QUERY SCOPES
    // ==========================================

    public function scopeOrderedByPositionAndAge($query)
    {
        return $query->orderByRaw("
            CASE LOWER(position)
                WHEN 'goalkeeper' THEN 1
                WHEN 'defender' THEN 2
                WHEN 'midfielder' THEN 3
                WHEN 'striker' THEN 4
                ELSE 999
            END
        ")->orderBy('age', 'asc');
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('approval_type', ['full', 'temporary']);
    }

    public function scopeFullyApproved($query)
    {
        return $query->where('approval_type', 'full');
    }

    // ==========================================
    // APPROVAL CHECKS
    // ==========================================

    /**
     * Check if player has any form of approval (full or temporary)
     */
    public function isApproved(): bool
    {
        return in_array($this->approval_type, ['full', 'temporary']);
    }

    /**
     * Check if player has full approval
     */
    public function isFullyApproved(): bool
    {
        return $this->approval_type === 'full';
    }

    /**
     * Check if player has temporary approval
     */
    public function isTemporarilyApproved(): bool
    {
        return $this->approval_type === 'temporary';
    }

    /**
     * Check if player's temporary approval is still valid
     */
    public function isTemporaryApprovalValid(): bool
    {
        if ($this->approval_type !== 'temporary' || !$this->temporary_approval_expires_at) {
            return false;
        }

        return now()->lessThanOrEqualTo($this->temporary_approval_expires_at);
    }

    /**
     * Check if player's temporary approval has expired
     */
    public function isTemporaryApprovalExpired(): bool
    {
        if ($this->approval_type !== 'temporary' || !$this->temporary_approval_expires_at) {
            return false;
        }

        return now()->greaterThan($this->temporary_approval_expires_at);
    }

    /**
     * Get remaining days for temporary approval
     */
    public function getTemporaryApprovalDaysRemaining(): int
    {
        if ($this->approval_type !== 'temporary' || !$this->temporary_approval_expires_at) {
            return 0;
        }

        $remaining = now()->diffInDays($this->temporary_approval_expires_at, false);
        return max(0, $remaining);
    }

    // ==========================================
    // APPROVAL MANAGEMENT
    // ==========================================

    public function grantFullApproval()
    {
        $this->update([
            'approval_type' => 'full',
            'temporary_approval_granted_at' => null,
            'temporary_approval_expires_at' => null,
            'temporary_approval_notes' => null,
        ]);

        return $this;
    }

    public function grantTemporaryApproval($notes = null)
    {
        $expiryDate = now()->addDays(7);

        $this->update([
            'approval_type' => 'temporary',
            'temporary_approval_granted_at' => now(),
            'temporary_approval_expires_at' => $expiryDate,
            'temporary_approval_notes' => $notes,
        ]);

        return $this;
    }

    public function revokeApproval()
    {
        $this->update([
            'approval_type' => 'none',
            'temporary_approval_granted_at' => null,
            'temporary_approval_expires_at' => null,
            'temporary_approval_notes' => null,
        ]);

        return $this;
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    public function canAccessPortal()
    {
        return $this->isApproved() && $this->isTemporaryApprovalValid();
    }

    public function needsDocumentCompletion()
    {
        return !$this->documents_completed;
    }

    public function getTotalGoals()
    {
        return $this->goals + ($this->gameStatistics->sum('goals_scored') ?? 0);
    }

    public function getTotalAssists()
    {
        return $this->assists + ($this->gameStatistics->sum('assists') ?? 0);
    }

    // Billing methods
    public function getMonthlyFee()
    {
        // Use payment category if assigned, otherwise fall back to fee_category
        if ($this->paymentCategory) {
            return $this->paymentCategory->monthly_amount;
        }
        return $this->fee_category === 'B' ? 500 : 200;
    }

    public function getJoiningFee()
    {
        if ($this->paymentCategory) {
            return $this->paymentCategory->joining_fee;
        }
        return $this->fee_category === 'B' ? 1000 : 100;
    }

    public function getCurrentOutstandingBalance()
    {
        $latestBilling = $this->monthlyBillings()->latest('month_year')->first();
        return $latestBilling ? $latestBilling->outstanding_balance : 0;
    }

    public function getPendingPayments()
    {
        return $this->payments()->pending()->orderBy('due_date', 'asc')->get();
    }

    public function getOverduePayments()
    {
        return $this->payments()->overdue()->orderBy('due_date', 'asc')->get();
    }

    public function getCompletedPayments()
    {
        return $this->payments()->completed()->orderBy('paid_at', 'desc')->get();
    }

    public function getTotalPaidAmount()
    {
        return $this->payments()->completed()->sum('amount');
    }

    public function getTotalPendingAmount()
    {
        return $this->payments()->pending()->sum('amount');
    }

    public function getTotalOverdueAmount()
    {
        return $this->payments()->overdue()->sum('amount');
    }

    public function hasPendingPayments(): bool
    {
        return $this->payments()->pending()->exists();
    }

    public function hasOverduePayments(): bool
    {
        return $this->payments()->overdue()->exists();
    }

    public function getNextPaymentDueDate()
    {
        $pending = $this->getPendingPayments()->first();
        return $pending ? $pending->due_date : null;
    }

    public function createMonthlyBilling($monthYear)
    {
        $openingBalance = $this->getCurrentOutstandingBalance();
        $monthlyFee = $this->getMonthlyFee();

        return $this->monthlyBillings()->create([
            'month_year' => $monthYear,
            'opening_balance' => $openingBalance,
            'monthly_fee' => $monthlyFee,
            'amount_paid' => 0,
            'closing_balance' => $openingBalance + $monthlyFee,
            'balance_carried_forward' => $openingBalance + $monthlyFee,
        ]);
    }

    public function applyPayment($amount, $monthAppliedTo = null)
    {
        $billing = $this->getOrCreateMonthlyBilling($monthAppliedTo ?: now()->format('Y-m'));

        // Apply payment: first to opening balance, then to current month fee
        $remainingPayment = $amount;

        // Pay off opening balance first
        if ($billing->opening_balance > 0) {
            $payToOpening = min($remainingPayment, $billing->opening_balance);
            $billing->opening_balance -= $payToOpening;
            $remainingPayment -= $payToOpening;
        }

        // Then apply to monthly fee
        if ($remainingPayment > 0 && $billing->amount_paid < $billing->monthly_fee) {
            $payToFee = min($remainingPayment, $billing->monthly_fee - $billing->amount_paid);
            $billing->amount_paid += $payToFee;
            $remainingPayment -= $payToFee;
        }

        // Recalculate balances
        $billing->closing_balance = $billing->opening_balance + $billing->monthly_fee - $billing->amount_paid;
        $billing->balance_carried_forward = max(0, $billing->closing_balance);

        $billing->save();

        return $billing;
    }

    private function getOrCreateMonthlyBilling($monthYear)
    {
        return $this->monthlyBillings()->where('month_year', $monthYear)->first()
            ?? $this->createMonthlyBilling($monthYear);
    }
}
