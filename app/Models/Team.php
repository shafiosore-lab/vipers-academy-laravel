<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'age_group',
        'category',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope('organization', function ($query) {
            $organizationId = auth()->check() ? auth()->user()->organization_id : null;
            if ($organizationId) {
                $query->where('organization_id', $organizationId);
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

    public function homeMatches(): HasMany
    {
        return $this->hasMany(Event::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(Event::class, 'away_team_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Helper methods
    public static function getAgeGroups(): array
    {
        return [
            'U6' => 'Under 6',
            'U7' => 'Under 7',
            'U8' => 'Under 8',
            'U9' => 'Under 9',
            'U10' => 'Under 10',
            'U11' => 'Under 11',
            'U12' => 'Under 12',
            'U13' => 'Under 13',
            'U14' => 'Under 14',
            'U15' => 'Under 15',
            'U16' => 'Under 16',
            'U17' => 'Under 17',
            'U18' => 'Under 18',
            'U19' => 'Under 19',
            'Senior' => 'Senior',
            'Veteran' => 'Veteran',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'academy' => 'Academy',
            'youth' => 'Youth',
            'senior' => 'Senior',
            'veteran' => 'Veteran',
        ];
    }

    public function getFullName(): string
    {
        return $this->age_group ? "{$this->name} ({$this->age_group})" : $this->name;
    }
}
