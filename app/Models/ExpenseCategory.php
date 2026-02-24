<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'group_name',
        'is_system',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope('organization', function ($query) {
            $organizationId = auth()->check() ? auth()->user()->organization_id : null;
            if ($organizationId) {
                // Include system categories (is_system = true) OR org-specific categories
                $query->where(function($q) use ($organizationId) {
                    $q->where('is_system', true)
                      ->orWhere('organization_id', $organizationId)
                      ->orWhereNull('organization_id');
                });
            }
        });
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeOrganization($query, $organizationId = null)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeGrouped($query)
    {
        return $query->whereNotNull('group_name')->orderBy('group_name');
    }

    // Helper methods
    public static function getFootballCategories(): array
    {
        return [
            'player_wages' => 'Player Wages & Bonuses',
            'coach_salaries' => 'Coach & Staff Salaries',
            'equipment' => 'Equipment & Gear',
            'travel' => 'Travel & Transportation',
            'match_day' => 'Match Day Expenses',
            'training_facility' => 'Training Facility',
            'tournament_fees' => 'Tournament Fees',
            'medical' => 'Medical & Sports Science',
            'merchandise' => 'Merchandise',
            'marketing' => 'Marketing & Promotion',
            'administrative' => 'Administrative Costs',
            'utilities' => 'Utilities & Maintenance',
            'insurance' => 'Insurance',
            'legal' => 'Legal & Professional',
            'youth_development' => 'Youth Development',
            'facility_rentals' => 'Facility Rentals',
            'other' => 'Other Expenses',
        ];
    }

    public static function getDefaultCategories(): array
    {
        $categories = [
            [
                'name' => 'Player Wages & Bonuses',
                'slug' => 'player_wages',
                'description' => 'Salaries, wages, and bonuses for players',
                'icon' => 'users',
                'color' => '#10b981',
            ],
            [
                'name' => 'Coach & Staff Salaries',
                'slug' => 'coach_salaries',
                'description' => 'Salaries for coaches, technical staff, and support personnel',
                'icon' => 'user-check',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Equipment & Gear',
                'slug' => 'equipment',
                'description' => 'Balls, cones, jerseys, training gear, and sports equipment',
                'icon' => 'package',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Travel & Transportation',
                'slug' => 'travel',
                'description' => 'Team travel, transportation, and accommodation',
                'icon' => 'truck',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Match Day Expenses',
                'slug' => 'match_day',
                'description' => 'Stadium rental, match officials, security, and match-day operations',
                'icon' => 'flag',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Training Facility',
                'slug' => 'training_facility',
                'description' => 'Pitch maintenance, training ground costs, and facility upgrades',
                'icon' => 'home',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Tournament Fees',
                'slug' => 'tournament_fees',
                'description' => 'Entry fees for tournaments and competitions',
                'icon' => 'award',
                'color' => '#ec4899',
            ],
            [
                'name' => 'Medical & Sports Science',
                'slug' => 'medical',
                'description' => 'Medical supplies, physiotherapy, sports science, and rehabilitation',
                'icon' => 'heart',
                'color' => '#14b8a6',
            ],
            [
                'name' => 'Merchandise',
                'slug' => 'merchandise',
                'description' => 'Jerseys, scarves, and club merchandise for resale',
                'icon' => 'shopping-bag',
                'color' => '#f97316',
            ],
            [
                'name' => 'Marketing & Promotion',
                'slug' => 'marketing',
                'description' => 'Advertising, promotions, and marketing campaigns',
                'icon' => 'megaphone',
                'color' => '#a855f7',
            ],
            [
                'name' => 'Administrative Costs',
                'slug' => 'administrative',
                'description' => 'Office supplies, printing, and general administration',
                'icon' => 'briefcase',
                'color' => '#6b7280',
            ],
            [
                'name' => 'Utilities & Maintenance',
                'slug' => 'utilities',
                'description' => 'Electricity, water, internet, and facility maintenance',
                'icon' => 'zap',
                'color' => '#eab308',
            ],
            [
                'name' => 'Insurance',
                'slug' => 'insurance',
                'description' => 'Player insurance, liability insurance, and club insurance',
                'icon' => 'shield',
                'color' => '#0ea5e9',
            ],
            [
                'name' => 'Legal & Professional',
                'slug' => 'legal',
                'description' => 'Legal fees, accounting, and professional services',
                'icon' => 'file-text',
                'color' => '#64748b',
            ],
            [
                'name' => 'Youth Development',
                'slug' => 'youth_development',
                'description' => 'Academy costs, youth scouting, and development programs',
                'icon' => 'graduation-cap',
                'color' => '#22c55e',
            ],
            [
                'name' => 'Facility Rentals',
                'slug' => 'facility_rentals',
                'description' => 'Rental costs for training grounds and match venues',
                'icon' => 'building',
                'color' => '#84cc16',
            ],
            [
                'name' => 'Other Expenses',
                'slug' => 'other',
                'description' => 'Miscellaneous expenses not covered by other categories',
                'icon' => 'more-horizontal',
                'color' => '#9ca3af',
            ],
        ];

        return $categories;
    }
}
