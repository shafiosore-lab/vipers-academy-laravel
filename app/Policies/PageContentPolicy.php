<?php

namespace App\Policies;

use App\Models\PageContent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PageContentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-admin') ||
               $user->hasRole('admin') ||
               $user->hasRole('org-admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PageContent $pageContent): bool
    {
        // SuperAdmin can view everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // OrgAdmin can view their organization's content or global content
        if ($user->hasRole('org-admin')) {
            $organizationId = $user->organization_id;

            // Can view global content
            if (is_null($pageContent->organization_id)) {
                return true;
            }

            // Can view their organization's content
            return $pageContent->organization_id === $organizationId;
        }

        // Regular admin can view
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super-admin') ||
               $user->hasRole('admin') ||
               $user->hasRole('org-admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PageContent $pageContent): bool
    {
        // SuperAdmin can update everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // OrgAdmin can only update their organization's content
        if ($user->hasRole('org-admin')) {
            $organizationId = $user->organization_id;

            // Cannot update global content
            if (is_null($pageContent->organization_id)) {
                return false;
            }

            return $pageContent->organization_id === $organizationId;
        }

        // Regular admin can update
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PageContent $pageContent): bool
    {
        // SuperAdmin can delete everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // OrgAdmin can only delete their organization's content
        if ($user->hasRole('org-admin')) {
            $organizationId = $user->organization_id;

            // Cannot delete global content
            if (is_null($pageContent->organization_id)) {
                return false;
            }

            return $pageContent->organization_id === $organizationId;
        }

        // Regular admin can delete
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create global content.
     * Only SuperAdmin can create global content (organization_id = null)
     */
    public function createGlobal(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can manage organization-specific content.
     * OrgAdmin can only manage their organization's content
     */
    public function manageOrganization(User $user, int $organizationId): bool
    {
        // SuperAdmin can manage any organization
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // OrgAdmin can only manage their own organization
        if ($user->hasRole('org-admin')) {
            return $user->organization_id === $organizationId;
        }

        return false;
    }
}
