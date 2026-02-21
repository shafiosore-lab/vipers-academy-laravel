<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SubscriptionPlan;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'is_default',
        'min_plan_level',
        'allowed_plan_slugs',
        'required_features',
        'level',
        'module',
        'is_subscription_restricted',
        'is_template',
        'organization_id',
        'parent_role_id',
        'inherit_permissions',
        'combined_role_ids',
        'name_key',
        'description_key',
        'department',
        'metadata',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_subscription_restricted' => 'boolean',
        'is_template' => 'boolean',
        'inherit_permissions' => 'boolean',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'min_plan_level' => 'integer',
        'level' => 'integer',
    ];

    /**
     * Get the users that belong to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the permissions that belong to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get the parent role (for role inheritance).
     */
    public function parentRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'parent_role_id');
    }

    /**
     * Get child roles (roles that inherit from this role).
     */
    public function childRoles(): HasMany
    {
        return $this->hasMany(Role::class, 'parent_role_id');
    }

    /**
     * Get the organization this role belongs to.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get module permissions for this role.
     */
    public function modulePermissions(): BelongsToMany
    {
        return $this->belongsToMany(ModulePermission::class, 'role_module_permissions')
            ->withPivot('allowed_actions', 'is_inherited')
            ->withTimestamps();
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Assign a permission to this role.
     */
    public function assignPermission(string|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : Permission::where('slug', $permission)->first()->id;
        $this->permissions()->attach($permissionId);
    }

    /**
     * Remove a permission from this role.
     */
    public function removePermission(string|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : Permission::where('slug', $permission)->first()->id;
        $this->permissions()->detach($permissionId);
    }

    /**
     * Get all permissions for this role (including inherited permissions).
     */
    public function getAllPermissions()
    {
        // If role has combined roles (hybrid), get permissions from all
        if ($this->combined_role_ids) {
            $combinedIds = explode(',', $this->combined_role_ids);
            $permissions = collect();

            foreach ($combinedIds as $roleId) {
                $role = Role::find(trim($roleId));
                if ($role) {
                    $permissions = $permissions->merge($role->permissions);
                }
            }

            // Add own permissions
            $permissions = $permissions->merge($this->permissions);

            return $permissions->unique('id')->values();
        }

        // If inheritance is enabled and has parent role, get parent permissions
        if ($this->inherit_permissions && $this->parent_role_id) {
            $parentRole = Role::find($this->parent_role_id);
            if ($parentRole) {
                $parentPermissions = $parentRole->getAllPermissions();
                $ownPermissions = $this->permissions;

                return $parentPermissions->merge($ownPermissions)->unique('id')->values();
            }
        }

        // Default: return own permissions
        return $this->permissions;
    }

    /**
     * Check if this role is accessible for a given subscription plan.
     */
    public function isAccessibleForPlan(?SubscriptionPlan $plan): bool
    {
        // If role is not subscription restricted, it's available to everyone
        if (!$this->is_subscription_restricted) {
            return true;
        }

        // If no plan provided, restrict access
        if (!$plan) {
            return false;
        }

        // Check minimum plan level
        if ($this->min_plan_level > 0) {
            $planOrder = $this->getPlanOrder($plan->slug);
            if ($planOrder < $this->min_plan_level) {
                return false;
            }
        }

        // Check allowed plan slugs
        if ($this->allowed_plan_slugs) {
            $allowedSlugs = explode(',', $this->allowed_plan_slugs);
            if (!in_array($plan->slug, $allowedSlugs)) {
                return false;
            }
        }

        // Check required features
        if ($this->required_features) {
            $requiredFeatures = explode(',', $this->required_features);
            $planFeatures = $plan->features ?? [];
            foreach ($requiredFeatures as $feature) {
                if (!isset($planFeatures[$feature]) || $planFeatures[$feature] !== true) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get plan order for comparison (higher = more premium).
     */
    private function getPlanOrder(string $planSlug): int
    {
        return match($planSlug) {
            'starter' => 1,
            'professional' => 2,
            'enterprise' => 3,
            default => 0,
        };
    }

    /**
     * Get allowed plan slugs as array.
     */
    public function getAllowedPlanSlugsAttribute(): array
    {
        if (empty($this->attributes['allowed_plan_slugs'])) {
            return [];
        }
        return array_map('trim', explode(',', $this->attributes['allowed_plan_slugs']));
    }

    /**
     * Get required features as array.
     */
    public function getRequiredFeaturesAttribute(): array
    {
        if (empty($this->attributes['required_features'])) {
            return [];
        }
        return array_map('trim', explode(',', $this->attributes['required_features']));
    }

    /**
     * Scope to get roles accessible for a specific plan.
     */
    public function scopeAccessibleForPlan($query, ?SubscriptionPlan $plan)
    {
        if (!$plan) {
            return $query->where('is_subscription_restricted', false);
        }

        return $query->where(function ($q) use ($plan) {
            $q->where('is_subscription_restricted', false)
              ->orWhere(function ($q2) use ($plan) {
                  $q2->where('is_subscription_restricted', true)
                     ->where(function ($q3) use ($plan) {
                         // Check min_plan_level
                         $planOrder = $this->getPlanOrder($plan->slug);
                         $q3->where('min_plan_level', '<=', $planOrder)
                            ->orWhere('min_plan_level', 0);
                     })
                     ->orWhere(function ($q3) use ($plan) {
                         // Check allowed_plan_slugs
                         $allowedSlugs = explode(',', $plan->slug);
                         $q3->whereRaw("FIND_IN_SET('$plan->slug', REPLACE(allowed_plan_slugs, ' ', ''))");
                     })
                     ->orWhere(function ($q3) use ($plan) {
                         // Check required_features
                         $planFeatures = $plan->features ?? [];
                         $requiredFeatures = array_keys(array_filter($planFeatures, fn($v) => $v === true));
                         if (!empty($requiredFeatures)) {
                             foreach ($requiredFeatures as $feature) {
                                 $q3->whereRaw("FIND_IN_SET('$feature', REPLACE(required_features, ' ', ''))");
                             }
                         }
                     });
              });
        });
    }

    /**
     * Scope to get roles by type and module.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get roles by module.
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Get the display name for the role restriction status.
     */
    public function getRestrictionStatusAttribute(): string
    {
        if (!$this->is_subscription_restricted) {
            return 'Available to all plans';
        }

        $restrictions = [];

        if ($this->min_plan_level > 0) {
            $planName = match($this->min_plan_level) {
                1 => 'Starter',
                2 => 'Professional',
                3 => 'Enterprise',
                default => 'Custom',
            };
            $restrictions[] = "Min: $planName";
        }

        if ($this->allowed_plan_slugs) {
            $restrictions[] = 'Plans: ' . $this->allowed_plan_slugs;
        }

        if ($this->required_features) {
            $restrictions[] = 'Features: ' . $this->required_features;
        }

        return implode(', ', $restrictions) ?: 'Restricted';
    }
}
