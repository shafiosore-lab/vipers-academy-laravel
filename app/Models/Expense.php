<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'organization_id',
        'reference',
        'budget_item_id',
        'budget_plan_id',
        'expense_category_id',
        'team_id',
        'event_id',
        'player_id',
        'title',
        'description',
        'amount',
        'quantity',
        'unit_price',
        'expense_date',
        'status',
        'payment_method',
        'receipt_number',
        'vendor',
        'attachment_path',
        'approved_by',
        'approved_at',
        'paid_at',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope('organization', function ($query) {
            $organizationId = auth()->check() ? auth()->user()->organization_id : null;
            if ($organizationId) {
                $query->where('organization_id', $organizationId);
            }
        });
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function budgetItem(): BelongsTo
    {
        return $this->belongsTo(BudgetItem::class);
    }

    public function budgetPlan(): BelongsTo
    {
        return $this->belongsTo(BudgetPlan::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('expense_category_id', $categoryId);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('expense_date', [$from, $to]);
    }

    // Helper methods
    public function getTotalAmount(): float
    {
        return (float) ($this->amount * $this->quantity);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'paid' => 'success',
            default => 'secondary'
        };
    }

    public function approve(User $user): void
    {
        $this->status = 'approved';
        $this->approved_by = $user->id;
        $this->save();
    }

    public function reject(User $user): void
    {
        $this->status = 'rejected';
        $this->approved_by = $user->id;
        $this->save();
    }

    public function markAsPaid(): void
    {
        $this->status = 'paid';
        $this->save();
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'EXP-' . date('Ymd') . '-' . strtoupper(substr(md5(microtime()), 0, 6));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Cash',
            'mpesa' => 'M-Pesa',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'card' => 'Card',
            'online' => 'Online Payment',
        ];
    }
}
