<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
        'product_data',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'product_data' => 'array',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price for this cart item.
     */
    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Scope for authenticated user cart items.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for guest user cart items.
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get cart items for current user/session.
     */
    public static function getCurrentCart()
    {
        $query = static::with('product')->whereHas('product');

        if (auth()->check()) {
            $query->forUser(auth()->id());
        } else {
            $query->forSession(session()->getId());
        }

        return $query->get();
    }

    /**
     * Add item to cart.
     */
    public static function addItem($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        if (!$product->is_active) {
            throw new \Exception('Product is not available.');
        }

        if ($product->stock < $quantity) {
            throw new \Exception('Insufficient stock.');
        }

        $cartData = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price,
            'product_data' => [
                'name' => $product->name,
                'sku' => $product->sku,
                'images' => $product->images,
            ],
        ];

        if (auth()->check()) {
            $cartData['user_id'] = auth()->id();
        } else {
            $cartData['session_id'] = session()->getId();
        }

        return static::updateOrCreate(
            [
                'user_id' => $cartData['user_id'] ?? null,
                'session_id' => $cartData['session_id'] ?? null,
                'product_id' => $productId,
            ],
            $cartData
        );
    }

    /**
     * Update cart item quantity.
     */
    public function updateQuantity($quantity)
    {
        if ($quantity <= 0) {
            $this->delete();
            return null;
        }

        if ($this->product->stock < $quantity) {
            throw new \Exception('Insufficient stock.');
        }

        $this->update(['quantity' => $quantity]);
        return $this;
    }

    /**
     * Remove item from cart.
     */
    public function remove()
    {
        return $this->delete();
    }

    /**
     * Get cart total.
     */
    public static function getCartTotal()
    {
        $cartItems = static::getCurrentCart();
        return $cartItems->sum('total');
    }

    /**
     * Get cart item count.
     */
    public static function getCartCount()
    {
        $cartItems = static::getCurrentCart();
        return $cartItems->sum('quantity');
    }

    /**
     * Clear cart for current user/session.
     */
    public static function clearCart()
    {
        $query = static::query();

        if (auth()->check()) {
            $query->forUser(auth()->id());
        } else {
            $query->forSession(session()->getId());
        }

        return $query->delete();
    }

    /**
     * Transfer guest cart to authenticated user.
     */
    public static function transferGuestCartToUser($userId)
    {
        return static::where('session_id', session()->getId())
            ->whereNull('user_id')
            ->update(['user_id' => $userId, 'session_id' => null]);
    }
}
