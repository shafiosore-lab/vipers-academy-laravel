<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'website',
        'description',
        'status',
        'subscription_id',
        'subscription_plan_id',
        'trial_ends_at',
        'subscription_ends_at',
        'max_users',
        'max_players',
        'billing_cycle',
        'created_by',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'max_users' => 'integer',
        'max_players' => 'integer',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_TRIAL = 'trial';
    const STATUS_PENDING = 'pending';
    const STATUS_SPAM = 'suspended_spam';

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function letterheads(): HasMany
    {
        return $this->hasMany(OrganizationLetterhead::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OrganizationDocument::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function branding(): HasMany
    {
        return $this->hasMany(OrganizationBranding::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOnTrial($query)
    {
        return $query->where('status', self::STATUS_TRIAL);
    }

    public function scopeActiveSubscription($query)
    {
        return $query->whereHas('subscription', function ($q) {
            $q->where('status', 'active');
        });
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    public function scopeSpam($query)
    {
        return $query->where('status', self::STATUS_SPAM);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeExpiredTrial($query)
    {
        return $query->where('status', self::STATUS_TRIAL)
            ->where('trial_ends_at', '<', now());
    }

    public function scopeWithActiveSubscription($query)
    {
        return $query->where(function($q) {
            $q->where('status', self::STATUS_ACTIVE)
              ->orWhere(function($subQ) {
                  $subQ->where('status', self::STATUS_TRIAL)
                       ->where('trial_ends_at', '>', now());
              });
        });
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isOnTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL &&
               $this->trial_ends_at &&
               $this->trial_ends_at->isFuture();
    }

    public function isTrialExpired(): bool
    {
        return $this->status === self::STATUS_TRIAL &&
               $this->trial_ends_at &&
               $this->trial_ends_at->isPast();
    }

    public function hasActiveSubscription(): bool
    {
        // Check if on active trial
        if ($this->isOnTrial()) {
            return true;
        }

        // Check if has active paid subscription
        return $this->subscription &&
               $this->subscription->status === 'active' &&
               (!$this->subscription->ends_at || $this->subscription->ends_at->isFuture());
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isSpam(): bool
    {
        return $this->status === self::STATUS_SPAM;
    }

    public function canAccessDashboard(): bool
    {
        // Active organizations can access
        if ($this->isActive()) {
            return true;
        }

        // Organizations on unexpired trial can access
        if ($this->isOnTrial()) {
            return true;
        }

        // Suspended or spam cannot access
        return false;
    }

    public function canAddUser(): bool
    {
        if (!$this->max_users) {
            return true; // Unlimited
        }
        return $this->users()->count() < $this->max_users;
    }

    public function canAddPlayer(): bool
    {
        if (!$this->max_players) {
            return true; // Unlimited
        }
        return $this->players()->count() < $this->max_players;
    }

    public function getTotalUsers(): int
    {
        return $this->users()->count();
    }

    public function getTotalPlayers(): int
    {
        return $this->players()->count();
    }

    public function getUsagePercentage(string $type): float
    {
        $max = $type === 'users' ? $this->max_users : $this->max_players;
        $current = $type === 'users' ? $this->getTotalUsers() : $this->getTotalPlayers();

        if (!$max) {
            return 0;
        }

        return round(($current / $max) * 100, 2);
    }

    public function hasFeature(string $feature): bool
    {
        if (!$this->subscriptionPlan) {
            return false;
        }

        $features = $this->subscriptionPlan->features ?? [];
        return isset($features[$feature]) && $features[$feature] === true;
    }

    public function getPlanName(): string
    {
        return $this->subscriptionPlan ? $this->subscriptionPlan->name : 'No Plan';
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_INACTIVE => 'secondary',
            self::STATUS_SUSPENDED => 'danger',
            self::STATUS_TRIAL => 'warning',
            self::STATUS_PENDING => 'info',
            default => 'secondary'
        };
    }

    public static function generateSlug(string $name): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
