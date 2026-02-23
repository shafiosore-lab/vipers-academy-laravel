<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'gateway_name',
        'gateway_type',
        'status',
        'is_primary',
        'description',
        'api_key',
        'api_secret',
        'sender_id',
        'account_id',
        'settings',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'settings' => 'array',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPrimary(): bool
    {
        return $this->is_primary === true;
    }

    public static function getPrimary(): ?self
    {
        return static::where('is_primary', true)->first();
    }

    public static function getActive(): array
    {
        return static::where('status', 'active')->get()->toArray();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
