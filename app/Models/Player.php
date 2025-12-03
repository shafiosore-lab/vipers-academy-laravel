<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'category',
        'position',
        'age',
        'jersey_number',
        'image_path',
        'bio',
        'goals',
        'assists',
        'appearances',
        'yellow_cards',
        'red_cards',
        'program_id',
        'registration_status',
        'approval_type',
        'documents_completed'
    ];

    // Position order for sorting
    const POSITION_ORDER = [
        'goalkeeper' => 1,
        'defender' => 2,
        'midfielder' => 3,
        'striker' => 4
    ];

    // Category order
    const CATEGORY_ORDER = [
        'under-13' => 1,
        'under-15' => 2,
        'under-17' => 3,
        'senior' => 4
    ];

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        return $this->name;
    }

    /**
     * Get position order for sorting
     */
    public function getPositionOrderAttribute()
    {
        return self::POSITION_ORDER[strtolower($this->position)] ?? 999;
    }

    /**
     * Get category order for sorting
     */
    public function getCategoryOrderAttribute()
    {
        return self::CATEGORY_ORDER[strtolower($this->category)] ?? 999;
    }

    /**
     * Get formatted category name
     */
    public function getFormattedCategoryAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }

    /**
     * Get formatted position name
     */
    public function getFormattedPositionAttribute()
    {
        return ucfirst($this->position);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('assets/img/players/' . $this->image_path))) {
            return asset('assets/img/players/' . $this->image_path);
        }
        return null;
    }

    /**
     * Scope to order players by position then age
     */
    public function scopeOrderedByPositionAndAge($query)
    {
        return $query->orderByRaw("
            CASE LOWER(position)
                WHEN 'goalkeeper' THEN 1
                WHEN 'defender' THEN 2
                WHEN 'midfielder' THEN 3
                WHEN 'striker' THEN 4
                ELSE 999
            END
        ")->orderBy('age', 'asc');
    }

    /**
     * Scope to filter by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
