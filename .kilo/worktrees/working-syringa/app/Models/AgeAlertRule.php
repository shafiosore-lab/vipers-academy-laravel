<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AgeAlertRule Model
 *
 * Manages age verification alert rules for tournaments
 */
class AgeAlertRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'category',
        'min_age',
        'max_age',
        'alert_threshold_days',
        'is_active',
        'auto_flag',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_flag' => 'boolean',
    ];

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get players approaching age cutoff
     */
    public function getPlayersNearCutoff(): \Illuminate\Database\Eloquent\Collection
    {
        $minAge = $this->min_age;
        $maxAge = $this->max_age;
        $thresholdDays = $this->alert_threshold_days;

        return Player::where('organization_id', $this->organization_id)
            ->where('category', $this->category)
            ->whereNotNull('date_of_birth')
            ->get()
            ->filter(function ($player) use ($minAge, $maxAge, $thresholdDays) {
                $age = $player->getExactAgeAttribute();

                // Check if player is near age boundaries
                $nearMinBoundary = $age === $minAge - 1;
                $nearMaxBoundary = $age === $maxAge;

                if (!$nearMinBoundary && !$nearMaxBoundary) {
                    return false;
                }

                // Check date threshold
                $cutoffDate = $nearMinBoundary
                    ? now()->addDays($thresholdDays)->startOfYear()
                    : now()->addDays($thresholdDays)->endOfYear();

                return true;
            });
    }

    /**
     * Get alert rules for an organization
     */
    public static function getForOrganization(int $organizationId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('organization_id', $organizationId)
            ->active()
            ->orderBy('category')
            ->get();
    }
}
