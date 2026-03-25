<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait OrganizationScoped
{
    /**
     * Boot the organization scoped trait for the model.
     */
    protected static function bootOrganizationScoped()
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            $user = Auth::user();

            // SuperAdmin can see all records
            if ($user && $user->hasRole('super-admin')) {
                return;
            }

            // Apply organization scope for all other users
            if ($user && $user->organization_id) {
                $builder->where('organization_id', $user->organization_id);
            } else {
                // If user has no organization, return empty results
                $builder->whereNull('organization_id');
            }
        });
    }

    /**
     * Scope query to organization
     */
    public function scopeOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope query to current user's organization
     */
    public function scopeCurrentUserOrganization(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user && $user->organization_id) {
            return $query->where('organization_id', $user->organization_id);
        }

        return $query->whereNull('organization_id');
    }

    /**
     * Remove the organization global scope
     */
    public static function withoutOrganizationScope()
    {
        return static::withoutGlobalScope('organization');
    }

    /**
     * Check if model belongs to user's organization
     */
    public function belongsToUserOrganization(): bool
    {
        $user = Auth::user();

        // SuperAdmin can access all organizations
        if ($user && $user->hasRole('super-admin')) {
            return true;
        }

        // Check if model belongs to user's organization
        if ($user && $user->organization_id) {
            return $this->organization_id === $user->organization_id;
        }

        return false;
    }

    /**
     * Check if model can be accessed by user
     */
    public function canBeAccessedBy($user): bool
    {
        // SuperAdmin can access all models
        if ($user && $user->hasRole('super-admin')) {
            return true;
        }

        // Check if model belongs to user's organization
        if ($user && $user->organization_id) {
            return $this->organization_id === $user->organization_id;
        }

        return false;
    }
}
