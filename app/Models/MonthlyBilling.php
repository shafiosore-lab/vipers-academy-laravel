<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'month_year',
        'opening_balance',
        'monthly_fee',
        'amount_paid',
        'closing_balance',
        'balance_carried_forward',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'balance_carried_forward' => 'decimal:2',
    ];

    // Relationships
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    // Methods
    public function calculateBalances()
    {
        // opening_balance + monthly_fee = total_due
        $totalDue = $this->opening_balance + $this->monthly_fee;

        // total_due - amount_paid = closing_balance
        $this->closing_balance = $totalDue - $this->amount_paid;

        // closing_balance becomes balance_carried_forward for next month
        $this->balance_carried_forward = max(0, $this->closing_balance);

        $this->save();

        return $this;
    }

    public function getTotalDueAttribute()
    {
        return $this->opening_balance + $this->monthly_fee;
    }

    public function getOutstandingBalanceAttribute()
    {
        return max(0, $this->closing_balance);
    }
}
