<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'category',
        'position',
        'age',
        'date_of_birth',
        'gender',
        'school_category',
        'jersey_number',
        'image_path',
        'passport_photo_path',
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
        'organization_id',
        'guardian_id',
        'email',
        'parent_guardian_name',
        'parent_phone',
        'parent_whatsapp',
        'whatsapp_opt_in',
        'training_days',
        'monthly_contribution',
        'status',
        'fee_category', // 'A' for 200, 'B' for 500
        // Tournament registration fields
        'id_type',
        'id_number',
        'city',
        'registered_at',
        'registered_by',
    ];

    protected $casts = [
        'temporary_approval_granted_at' => 'datetime',
        'temporary_approval_expires_at' => 'datetime',
        'date_of_birth' => 'date',
        'registered_at' => 'datetime',
    ];

    const POSITION_ORDER = [
        'goalkeeper' => 1,
        'defender' => 2,
        'midfielder' => 3,
        'striker' => 4
    ];

    const CATEGORY_ORDER = [
        'under-10' => 1,
        'under-12' => 2,
        'under-13' => 3,
        'under-14' => 4,
        'under-15' => 5,
        'under-16' => 6,
        'under-17' => 7,
        'under-18' => 8,
        'under-20' => 9,
        'senior' => 10,
        'veteran' => 11
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
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
    // INJURY & AVAILABILITY RELATIONSHIPS
    // ==========================================

    public function injuries()
    {
        return $this->hasMany(PlayerInjury::class);
    }

    public function activeInjuries()
    {
        return $this->hasMany(PlayerInjury::class)->active();
    }

    public function availability()
    {
        return $this->hasMany(PlayerAvailability::class);
    }

    // ==========================================
    // CONTRACT & TRANSFER RELATIONSHIPS
    // ==========================================

    public function contracts()
    {
        return $this->hasMany(PlayerContract::class);
    }

    public function activeContract()
    {
        return $this->hasOne(PlayerContract::class)->active();
    }

    public function transfers()
    {
        return $this->hasMany(PlayerTransfer::class);
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    // ==========================================
    // ATTRIBUTES & ACCESSORS
    // ==========================================

    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            $name = $this->first_name;
            if ($this->middle_name) {
                $name .= ' ' . $this->middle_name;
            }
            $name .= ' ' . $this->last_name;
            return $name;
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

    public function getPassportPhotoUrlAttribute()
    {
        if ($this->passport_photo_path) {
            return Storage::disk('private')->url($this->passport_photo_path);
        }
        return asset('assets/img/default-passport.png');
    }

    public function getExactAgeAttribute()
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }
        return $this->age;
    }

    public function getIdTypeLabelAttribute()
    {
        return match($this->id_type) {
            'national_id' => 'National ID',
            'passport' => 'Passport',
            'birth_certificate' => 'Birth Certificate',
            'other' => 'Other',
            default => 'Unknown',
        };
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

    // Scope for gender filtering (boys/girls)
    public function scopeGender($query, $gender)
    {
        if ($gender && $gender !== 'all') {
            return $query->where('gender', $gender);
        }
        return $query;
    }

    // Scope for school category filtering (primary, junior_secondary, senior_secondary)
    public function scopeSchoolCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('school_category', $category);
        }
        return $query;
    }

    // Scope for organization filtering
    public function scopeForOrganization($query, $organizationId)
    {
        if ($organizationId) {
            return $query->where('organization_id', $organizationId);
        }
        return $query;
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

    // ==========================================
    // INJURY METHODS
    // ==========================================

    /**
     * Check if player has active injuries
     */
    public function hasActiveInjury(): bool
    {
        return $this->activeInjuries()->exists();
    }

    /**
     * Get active injury count
     */
    public function getActiveInjuryCount(): int
    {
        return $this->activeInjuries()->count();
    }

    /**
     * Get latest injury
     */
    public function getLatestInjury(): ?PlayerInjury
    {
        return $this->injuries()->latest('injury_date')->first();
    }

    /**
     * Record a new injury
     */
    public function recordInjury(array $data): PlayerInjury
    {
        return $this->injuries()->create(array_merge($data, [
            'organization_id' => $this->organization_id,
            'injury_date' => $data['injury_date'] ?? now()->toDateString(),
            'status' => PlayerInjury::STATUS_ACTIVE,
        ]));
    }

    // ==========================================
    // AVAILABILITY METHODS
    // ==========================================

    /**
     * Check availability for a specific date
     */
    public function getAvailabilityForDate($date): string
    {
        $availability = $this->availability()
            ->whereDate('availability_date', $date)
            ->first();

        return $availability ? $availability->status : PlayerAvailability::STATUS_AVAILABLE;
    }

    /**
     * Check if player is available on a date
     */
    public function isAvailableOn($date): bool
    {
        return $this->getAvailabilityForDate($date) === PlayerAvailability::STATUS_AVAILABLE;
    }

    /**
     * Set availability for a date
     */
    public function setAvailability($date, string $status, ?string $reason = null): void
    {
        $this->availability()->updateOrCreate(
            ['availability_date' => $date],
            [
                'organization_id' => $this->organization_id,
                'status' => $status,
                'reason' => $reason,
                'recorded_by' => auth()->id(),
            ]
        );

        // Update player's overall availability status
        $this->update([
            'availability_status' => $status,
            'last_availability_update' => now()->toDateString(),
        ]);
    }

    /**
     * Get upcoming unavailability
     */
    public function getUpcomingUnavailability(int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        return $this->availability()
            ->whereDate('availability_date', '>=', now())
            ->whereDate('availability_date', '<=', now()->addDays($days))
            ->whereIn('status', [PlayerAvailability::STATUS_UNAVAILABLE, PlayerAvailability::STATUS_TENTATIVE])
            ->orderBy('availability_date')
            ->get();
    }

    // ==========================================
    // CONTRACT METHODS
    // ==========================================

    /**
     * Check if player has active contract
     */
    public function hasActiveContract(): bool
    {
        return $this->activeContract()->exists();
    }

    /**
     * Get active contract
     */
    public function getActiveContract(): ?PlayerContract
    {
        return $this->activeContract;
    }

    /**
     * Get contract status
     */
    public function getContractStatus(): string
    {
        $contract = $this->getActiveContract();

        if (!$contract) {
            return 'no_contract';
        }

        if ($contract->isExpiringSoon(30)) {
            return 'expiring_soon';
        }

        return 'active';
    }

    /**
     * Create new contract
     */
    public function createContract(array $data): PlayerContract
    {
        return $this->contracts()->create(array_merge($data, [
            'organization_id' => $this->organization_id,
            'contract_number' => PlayerContract::generateContractNumber(),
            'status' => PlayerContract::STATUS_DRAFT,
        ]));
    }

    // ==========================================
    // TRANSFER METHODS
    // ==========================================

    /**
     * Check if player can be transferred
     */
    public function canTransfer(): array
    {
        $errors = [];

        // Check for active contract
        $contract = $this->getActiveContract();
        if ($contract && !$contract->release_clause) {
            $errors[] = 'Player has active contract without release clause';
        }

        // Check for active injuries
        if ($this->hasActiveInjury()) {
            $errors[] = 'Player has active injuries';
        }

        return $errors;
    }

    /**
     * Request transfer
     */
    public function requestTransfer(int $windowId, int $toTeamId, array $data = []): PlayerTransfer
    {
        return $this->transfers()->create(array_merge($data, [
            'transfer_window_id' => $windowId,
            'from_team_id' => $this->current_team_id,
            'to_team_id' => $toTeamId,
            'requested_date' => now()->toDateString(),
            'status' => PlayerTransfer::STATUS_PENDING,
            'requested_by' => auth()->id(),
        ]));
    }

    /**
     * Get transfer history
     */
    public function getTransferHistory(): \Illuminate\Database\Eloquent\Collection
    {
        return PlayerTransfer::getHistoryForPlayer($this->id);
    }
}
