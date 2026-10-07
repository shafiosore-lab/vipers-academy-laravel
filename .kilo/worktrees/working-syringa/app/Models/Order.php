<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'billing_address',
        'order_items',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'order_items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('order_status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Helper methods
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isDelivered(): bool
    {
        return $this->order_status === 'delivered';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, ['pending', 'processing']);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->order_status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'secondary',
            'refunded' => 'danger',
            default => 'secondary'
        };
    }

    public function getPaymentStatusBadgeClass(): string
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary'
        };
    }

    // Generate unique order number
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(microtime()), 0, 6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
