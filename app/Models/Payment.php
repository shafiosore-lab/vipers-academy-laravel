<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_reference',
        'transaction_id',
        'user_id',
        'payer_type',
        'payer_id',
        'payment_type',
        'description',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_gateway',
        'gateway_response',
        'notes',
        'paid_at',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payer(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    public function scopeByPayerType($query, $payerType)
    {
        return $query->where('payer_type', $payerType);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('payment_status', 'pending');
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->isPending();
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    public function getTypeBadgeClass(): string
    {
        return match($this->payment_type) {
            'registration_fee' => 'primary',
            'subscription_fee' => 'info',
            'program_fee' => 'success',
            'tournament_fee' => 'warning',
            'merchandise' => 'secondary',
            'donation' => 'danger',
            'sponsorship' => 'dark',
            default => 'secondary'
        };
    }

    // Generate unique payment reference
    public static function generatePaymentReference(): string
    {
        do {
            $reference = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(microtime()), 0, 8));
        } while (self::where('payment_reference', $reference)->exists());

        return $reference;
    }

    // Get formatted amount
    public function getFormattedAmount(): string
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    // Get payer name based on type
    public function getPayerName(): string
    {
        switch ($this->payer_type) {
            case 'player':
                $player = Player::find($this->payer_id);
                return $player ? $player->first_name . ' ' . $player->last_name : 'Unknown Player';
            case 'partner':
                $partner = User::find($this->payer_id);
                return $partner ? $partner->name : 'Unknown Partner';
            case 'customer':
                return $this->user ? $this->user->name : 'Unknown Customer';
            default:
                return 'Unknown';
        }
    }
}
