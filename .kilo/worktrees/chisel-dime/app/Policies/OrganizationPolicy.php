<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // SuperAdmin can view all organizations
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can only view their own organization
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        // SuperAdmin can view any organization
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can only view their assigned organization
        if ($user->hasRole('admin')) {
            return $user->organization_id === $organization->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only SuperAdmin can create organizations
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        // SuperAdmin can update any organization
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can only update their assigned organization
        if ($user->hasRole('admin')) {
            return $user->organization_id === $organization->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organization $organization): bool
    {
        // Only SuperAdmin can delete organizations
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organization $organization): bool
    {
        // Only SuperAdmin can restore organizations
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        // Only SuperAdmin can force delete organizations
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can manage organization branding
     */
    public function manageBranding(User $user, Organization $organization): bool
    {
        // SuperAdmin can manage all organization branding
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can manage their own organization branding
        if ($user->hasRole('admin')) {
            return $user->organization_id === $organization->id;
        }

        return false;
    }

    /**
     * Determine whether the user can manage organization documents
     */
    public function manageDocuments(User $user, Organization $organization): bool
    {
        // SuperAdmin can manage all organization documents
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can manage their own organization documents
        if ($user->hasRole('admin')) {
            return $user->organization_id === $organization->id;
        }

        return false;
    }

    /**
     * Determine whether the user can view organization analytics
     */
    public function viewAnalytics(User $user, Organization $organization): bool
    {
        // SuperAdmin can view all organization analytics
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can view their own organization analytics
        if ($user->hasRole('admin')) {
            return $user->organization_id === $organization->id;
        }

        return false;
    }
}
