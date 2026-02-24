<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetItem extends Model
{
    protected $fillable = [
        'organization_id',
        'budget_plan_id',
        'expense_category_id',
        'name',
        'description',
        'budgeted_amount',
        'spent_amount',
        'approved_amount',
        'quantity',
        'unit_price',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function budgetPlan(): BelongsTo
    {
        return $this->belongsTo(BudgetPlan::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes
    public function scopeOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Helper methods
    public function getBalance(): float
    {
        return (float) ($this->budgeted_amount - $this->spent_amount);
    }

    public function getExcess(): float
    {
        return (float) ($this->spent_amount - $this->budgeted_amount);
    }

    public function getSpentPercentage(): float
    {
        if ($this->budgeted_amount == 0) {
            return 0;
        }
        return round(($this->spent_amount / $this->budgeted_amount) * 100, 2);
    }

    public function isOverBudget(): bool
    {
        return $this->spent_amount > $this->budgeted_amount;
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'active' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function recalculateSpent(): void
    {
        $this->spent_amount = $this->expenses()->where('status', 'paid')->sum('amount');
        $this->approved_amount = $this->expenses()->where('status', 'approved')->sum('amount');
        $this->save();
    }

    public function getTotalAmount(): float
    {
        return (float) ($this->quantity * ($this->unit_price ?? 0));
    }
}
