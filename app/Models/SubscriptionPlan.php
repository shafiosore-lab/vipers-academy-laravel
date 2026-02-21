<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'max_users',
        'max_players',
        'max_staff',
        'features',
        'is_active',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_users' => 'integer',
        'max_players' => 'integer',
        'max_staff' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Default features for each plan
    const STARTER_FEATURES = [
        'players_management' => true,
        'teams' => true,
        'basic_reports' => true,
        'attendance_tracking' => true,
        'training_sessions' => true,
    ];

    const PROFESSIONAL_FEATURES = [
        'players_management' => true,
        'teams' => true,
        'basic_reports' => true,
        'advanced_reports' => true,
        'attendance_tracking' => true,
        'training_sessions' => true,
        'finance_module' => true,
        'parent_portal' => true,
        'player_portal' => true,
    ];

    const ENTERPRISE_FEATURES = [
        'players_management' => true,
        'teams' => true,
        'basic_reports' => true,
        'advanced_reports' => true,
        'custom_reports' => true,
        'attendance_tracking' => true,
        'training_sessions' => true,
        'finance_module' => true,
        'parent_portal' => true,
        'player_portal' => true,
        'api_access' => true,
        'custom_branding' => true,
        'priority_support' => true,
        'white_label' => true,
    ];

    // Relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'subscription_plan_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // Helper methods
    public function hasFeature(string $feature): bool
    {
        $features = $this->features ?? [];
        return isset($features[$feature]) && $features[$feature] === true;
    }

    public function getFormattedPrice(): string
    {
        return 'KES ' . number_format($this->price, 2);
    }

    public function getFeatureList(): array
    {
        return $this->features ?? [];
    }

    public function getActiveFeaturesCount(): int
    {
        return collect($this->features ?? [])->filter(fn($value) => $value === true)->count();
    }

    public function isUnlimited(string $type): bool
    {
        $value = match($type) {
            'users' => $this->max_users,
            'players' => $this->max_players,
            'staff' => $this->max_staff,
            default => null,
        };

        return $value === -1 || $value === null;
    }

    public function getLimit(string $type): string
    {
        return match($type) {
            'users' => $this->max_users == -1 ? 'Unlimited' : (string) $this->max_users,
            'players' => $this->max_players == -1 ? 'Unlimited' : (string) $this->max_players,
            'staff' => $this->max_staff == -1 ? 'Unlimited' : (string) $this->max_staff,
            default => '0',
        };
    }

    // Static methods for plan lookup
    public static function getBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->active()->first();
    }

    public static function getStarter(): ?self
    {
        return self::getBySlug('starter');
    }

    public static function getProfessional(): ?self
    {
        return self::getBySlug('professional');
    }

    public static function getEnterprise(): ?self
    {
        return self::getBySlug('enterprise');
    }

    // Permission management
    public function getPermissions(): array
    {
        $features = $this->features ?? [];
        return $features['permissions'] ?? [];
    }

    public function setPermissions(array $permissionIds): void
    {
        $features = $this->features ?? [];
        $features['permissions'] = $permissionIds;
        $this->features = $features;
    }

    public function hasPermission(string $permissionId): bool
    {
        return in_array($permissionId, $this->getPermissions());
    }

    public function assignPermission(string $permissionId): void
    {
        $permissions = $this->getPermissions();
        if (!in_array($permissionId, $permissions)) {
            $permissions[] = $permissionId;
            $this->setPermissions($permissions);
        }
    }

    public function removePermission(string $permissionId): void
    {
        $permissions = array_filter($this->getPermissions(), function($p) use ($permissionId) {
            return $p !== $permissionId;
        });
        $this->setPermissions(array_values($permissions));
    }
}
