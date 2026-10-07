<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'organization_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Equipment categories constants
    const CATEGORY_BALLS = 'balls';
    const CATEGORY_CONES = 'cones';
    const CATEGORY_JERSEYS = 'jerseys';
    const CATEGORY_SHORTS = 'shorts';
    const CATEGORY_SOCKS = 'socks';
    const CATEGORY_BOOTS = 'boots';
    const CATEGORY_GLOVES = 'gloves';
    const CATEGORY_BAGS = 'bags';
    const CATEGORY_TRAINING_GEAR = 'training_gear';
    const CATEGORY_MEDICAL = 'medical';
    const CATEGORY_OTHER = 'other';

    // Condition constants
    const CONDITION_NEW = 'new';
    const CONDITION_GOOD = 'good';
    const CONDITION_FAIR = 'fair';
    const CONDITION_POOR = 'poor';
    const CONDITION_DAMAGED = 'damaged';

    /**
     * Get the equipment items in this category
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }

    /**
     * Get total quantity of equipment in this category
     */
    public function getTotalQuantityAttribute()
    {
        return $this->equipment()->sum('quantity');
    }

    /**
     * Get available quantity (not distributed)
     */
    public function getAvailableQuantityAttribute()
    {
        return $this->equipment()->where('status', 'available')->sum('quantity');
    }

    /**
     * Get organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get condition options
     */
    public static function getConditionOptions()
    {
        return [
            self::CONDITION_NEW => 'New',
            self::CONDITION_GOOD => 'Good',
            self::CONDITION_FAIR => 'Fair',
            self::CONDITION_POOR => 'Poor',
            self::CONDITION_DAMAGED => 'Damaged',
        ];
    }

    /**
     * Get category icon
     */
    public function getIconHtmlAttribute()
    {
        return '<i class="fas fa-' . ($this->icon ?? 'box') . '"></i>';
    }
}
