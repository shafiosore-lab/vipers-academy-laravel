<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Scope;

class BudgetPlan extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'type',
        'year',
        'month',
        'season_label',
        'start_date',
        'end_date',
        'total_budget',
        'total_spent',
        'total_approved',
        'status',
        'notes',
        'objectives',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'total_budget' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'total_approved' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class)->orderBy('sort_order');
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

    public function scopeMonthly($query)
    {
        return $query->where('type', 'monthly');
    }

    public function scopeYearly($query)
    {
        return $query->where('type', 'yearly');
    }

    public function scopeSeasonal($query)
    {
        return $query->where('type', 'seasonal');
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
        return (float) ($this->total_budget - $this->total_spent);
    }

    public function getExcess(): float
    {
        return (float) ($this->total_spent - $this->total_budget);
    }

    public function getSpentPercentage(): float
    {
        if ($this->total_budget == 0) {
            return 0;
        }
        return round(($this->total_spent / $this->total_budget) * 100, 2);
    }

    public function isOverBudget(): bool
    {
        return $this->total_spent > $this->total_budget;
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'active' => 'success',
            'closed' => 'info',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'monthly' => 'Monthly',
            'yearly' => 'Yearly',
            'seasonal' => 'Seasonal (' . ($this->season_label ?? '') . ')',
            default => 'Unknown'
        };
    }

    public function getPeriodLabel(): string
    {
        if ($this->type === 'monthly' && $this->month) {
            return date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year));
        }
        return (string) $this->year;
    }

    public function recalculateTotals(): void
    {
        $this->total_spent = $this->expenses()->where('status', 'paid')->sum('amount');
        $this->total_approved = $this->expenses()->where('status', 'approved')->sum('amount');
        $this->save();
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'BUD-' . date('Ymd') . '-' . strtoupper(substr(md5(microtime()), 0, 6));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    public static function getAvailableYears(): array
    {
        $currentYear = date('Y');
        $years = [];
        for ($i = $currentYear - 1; $i <= $currentYear + 2; $i++) {
            $years[] = $i;
        }
        return $years;
    }

    public static function getAvailableMonths(): array
    {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }
}
