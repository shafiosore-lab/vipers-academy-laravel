<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'credentials',
        'bio',
        'photo_path',
        'linkedin_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope to get only active leaders ordered by display order
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }
}
