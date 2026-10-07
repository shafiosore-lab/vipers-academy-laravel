<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'registration_method',
        'preferences',
        'marketing_consent',
    ];

    protected $casts = [
        'preferences' => 'array',
        'marketing_consent' => 'boolean',
    ];

    /**
     * Get the user that owns this visitor profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for visitors registered through cart.
     */
    public function scopeFromCart($query)
    {
        return $query->where('registration_method', 'cart_registration');
    }

    /**
     * Check if visitor has marketing consent.
     */
    public function hasMarketingConsent()
    {
        return $this->marketing_consent ?? false;
    }
}
