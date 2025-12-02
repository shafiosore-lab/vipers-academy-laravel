<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_order_amount',
        'maximum_discount',
        'usage_limit',
        'usage_count',
        'is_active',
        'starts_at',
        'expires_at',
        'applicable_products',
        'applicable_categories',
        'user_restrictions',
        'first_time_only',
        'auto_apply',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'user_restrictions' => 'array',
        'first_time_only' => 'boolean',
        'auto_apply' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('usage_count < usage_limit');
                    });
    }

    public function scopeAutoApply($query)
    {
        return $query->where('auto_apply', true);
    }

    // Validation methods
    public function isValid(): bool
    {
        return $this->is_active &&
               $this->isWithinDateRange() &&
               !$this->isUsageLimitReached();
    }

    public function isWithinDateRange(): bool
    {
        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        return true;
    }

    public function isUsageLimitReached(): bool
    {
        return $this->usage_limit !== null && $this->usage_count >= $this->usage_limit;
    }

    public function canBeUsedBy(?int $userId, float $orderAmount): array
    {
        $errors = [];

        if (!$this->isValid()) {
            $errors[] = 'This coupon is not valid.';
        }

        if ($orderAmount < $this->minimum_order_amount) {
            $errors[] = "Minimum order amount of KSH " . number_format($this->minimum_order_amount) . " required.";
        }

        if ($this->first_time_only && $userId) {
            // Check if user has used this coupon before (would need order history)
            $hasUsedBefore = \App\Models\Order::where('user_id', $userId)
                                             ->whereJsonContains('order_items', ['coupon_code' => $this->code])
                                             ->exists();

            if ($hasUsedBefore) {
                $errors[] = 'This coupon can only be used once per customer.';
            }
        }

        if ($this->user_restrictions && $userId) {
            if (!in_array($userId, $this->user_restrictions)) {
                $errors[] = 'This coupon is not available for your account.';
            }
        }

        return $errors;
    }

    public function canBeUsedByUser(int $userId): bool
    {
        if (!$this->user_restrictions) {
            return true;
        }

        return in_array($userId, $this->user_restrictions);
    }

    // Discount calculation
    public function calculateDiscount(array $cartItems, float $subtotal): array
    {
        $discount = 0;
        $applicableItems = [];

        // Filter applicable items based on product/category restrictions
        $applicableItems = $this->filterApplicableItems($cartItems);

        // Calculate discount based on type
        switch ($this->type) {
            case 'percentage':
                $discountableAmount = collect($applicableItems)->sum(fn($item) => $item['total']);
                $calculatedDiscount = $discountableAmount * ($this->value / 100);

                // Apply maximum discount limit if set
                if ($this->maximum_discount && $calculatedDiscount > $this->maximum_discount) {
                    $calculatedDiscount = $this->maximum_discount;
                }

                $discount = $calculatedDiscount;
                break;

            case 'fixed_amount':
                if ($subtotal >= $this->minimum_order_amount) {
                    $discount = min($this->value, $subtotal); // Cannot exceed order total
                }
                break;

            case 'free_shipping':
                $discount = 0; // Free shipping handled separately in shipping calculation
                break;
        }

        return [
            'discount_amount' => round($discount, 2),
            'applicable_items' => $applicableItems,
            'coupon_type' => $this->type,
        ];
    }

    private function filterApplicableItems(array $cartItems): array
    {
        if (!$this->applicable_products && !$this->applicable_categories) {
            // No restrictions - all items applicable
            return $cartItems;
        }

        return array_filter($cartItems, function($item) {
            // Check specific products
            if ($this->applicable_products) {
                if (in_array($item['product_id'], $this->applicable_products)) {
                    return true;
                }
            }

            // Check categories
            if ($this->applicable_categories) {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product && in_array($product->category, $this->applicable_categories)) {
                    return true;
                }
            }

            return false;
        });
    }

    // Usage tracking
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public static function findByCode(string $code): ?self
    {
        return self::where('code', strtoupper($code))->first();
    }

    // Helper methods
    public function getDiscountDescription(): string
    {
        switch ($this->type) {
            case 'percentage':
                return "{$this->value}% off" . ($this->maximum_discount ? " (max. KSH {$this->maximum_discount})" : "");
            case 'fixed_amount':
                return "KSH {$this->value} off";
            case 'free_shipping':
                return "Free shipping";
            default:
                return "Discount";
        }
    }

    public function getStatusBadge(): string
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Inactive</span>';
        }

        if (!$this->isWithinDateRange()) {
            if ($this->starts_at && now()->lt($this->starts_at)) {
                return '<span class="badge bg-info">Not Started</span>';
            }
            if ($this->expires_at && now()->gt($this->expires_at)) {
                return '<span class="badge bg-danger">Expired</span>';
            }
        }

        if ($this->isUsageLimitReached()) {
            return '<span class="badge bg-warning">Fully Used</span>';
        }

        return '<span class="badge bg-success">Active</span>';
    }

    public function getUsagePercentage(): float
    {
        if (!$this->usage_limit) {
            return 0;
        }

        return min(($this->usage_count / $this->usage_limit) * 100, 100);
    }
}
