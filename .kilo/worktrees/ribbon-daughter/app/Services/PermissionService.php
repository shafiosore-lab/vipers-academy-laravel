<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    /**
     * Role hierarchy definition
     */
    const ROLE_HIERARCHY = [
        'super-admin' => [
            'admin',
            'tournament-director',
            'team-manager',
            'coach',
            'player',
            'parent',
            'scout'
        ],
        'admin' => [
            'tournament-director',
            'team-manager',
            'coach',
            'player',
            'parent',
            'scout'
        ],
        'tournament-director' => [
            'team-manager',
            'coach',
            'player',
            'parent',
            'scout'
        ],
        'team-manager' => [
            'coach',
            'player',
            'parent',
            'scout'
        ],
        'coach' => [
            'player',
            'parent'
        ],
        'player' => [],
        'parent' => [],
        'scout' => [],
    ];

    /**
     * Check if user can access organization
     */
    public function canAccessOrganization(User $user, Organization $organization): bool
    {
        // SuperAdmin can access all organizations
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can only access their assigned organization
        if ($user->hasRole('admin')) {
            return $user->organization_id === $organization->id;
        }

        // Other roles check their organization assignment
        return $user->organization_id === $organization->id;
    }

    /**
     * Check if user can manage tournament
     */
    public function canManageTournament(User $user, Tournament $tournament): bool
    {
        // SuperAdmin can manage all tournaments
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can only manage tournaments in their organization
        if ($user->hasRole('admin')) {
            return $user->organization_id === $tournament->organization_id;
        }

        // Tournament Director can manage tournaments in their organization
        if ($user->hasRole('tournament-director')) {
            return $user->organization_id === $tournament->organization_id;
        }

        return false;
    }

    /**
     * Check if user can manage team
     */
    public function canManageTeam(User $user, Team $team): bool
    {
        // SuperAdmin can manage all teams
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can manage teams in their organization
        if ($user->hasRole('admin')) {
            return $user->organization_id === $team->organization_id;
        }

        // Team Manager can manage their assigned teams
        if ($user->hasRole('team-manager')) {
            return $user->organization_id === $team->organization_id;
        }

        // Coach can manage teams they are assigned to
        if ($user->hasRole('coach')) {
            return $user->organization_id === $team->organization_id;
        }

        return false;
    }

    /**
     * Check if user can manage player
     */
    public function canManagePlayer(User $user, Player $player): bool
    {
        // SuperAdmin can manage all players
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can manage players in their organization
        if ($user->hasRole('admin')) {
            return $user->organization_id === $player->organization_id;
        }

        // Team Manager can manage players in their teams
        if ($user->hasRole('team-manager')) {
            return $user->organization_id === $player->organization_id;
        }

        // Coach can manage players in their teams
        if ($user->hasRole('coach')) {
            return $user->organization_id === $player->organization_id;
        }

        // Player can manage themselves
        if ($user->hasRole('player')) {
            return $user->id === $player->user_id;
        }

        // Parent can manage their children
        if ($user->hasRole('parent')) {
            return $user->organization_id === $player->organization_id;
        }

        return false;
    }

    /**
     * Check if user has specific role or higher
     */
    public function hasRoleOrHigher(User $user, string $role): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole($role)) {
            return true;
        }

        // Check role hierarchy
        foreach (self::ROLE_HIERARCHY as $superRole => $subRoles) {
            if ($user->hasRole($superRole) && in_array($role, $subRoles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can perform CRUD operation
     */
    public function canCRUD(User $user, string $resource, string $operation = 'create'): bool
    {
        // SuperAdmin can perform all CRUD operations
        if ($user->hasRole('super-admin')) {
            return true;
        }

        switch ($resource) {
            case 'tournament':
                return $this->canManageTournamentOperations($user, $operation);

            case 'team':
                return $this->canManageTeamOperations($user, $operation);

            case 'player':
                return $this->canManagePlayerOperations($user, $operation);

            case 'organization':
                return $this->canManageOrganizationOperations($user, $operation);

            default:
                return false;
        }
    }

    /**
     * Check tournament management permissions
     */
    protected function canManageTournamentOperations(User $user, string $operation): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin can manage all tournaments in org
        }

        if ($user->hasRole('tournament-director')) {
            return true; // Tournament Director can manage tournaments
        }

        return false;
    }

    /**
     * Check team management permissions
     */
    protected function canManageTeamOperations(User $user, string $operation): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin can manage all teams in org
        }

        if ($user->hasRole('team-manager')) {
            return true; // Team Manager can manage teams
        }

        if ($user->hasRole('coach')) {
            return $operation === 'read'; // Coach can only view teams
        }

        return false;
    }

    /**
     * Check player management permissions
     */
    protected function canManagePlayerOperations(User $user, string $operation): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin can manage all players in org
        }

        if ($user->hasRole('team-manager')) {
            return true; // Team Manager can manage players
        }

        if ($user->hasRole('coach')) {
            return $operation === 'read'; // Coach can only view players
        }

        if ($user->hasRole('player')) {
            return $operation === 'read' || $operation === 'update'; // Player can view and update own profile
        }

        if ($user->hasRole('parent')) {
            return $operation === 'read'; // Parent can only view
        }

        return false;
    }

    /**
     * Check organization management permissions
     */
    protected function canManageOrganizationOperations(User $user, string $operation): bool
    {
        if ($user->hasRole('super-admin')) {
            return true; // SuperAdmin can manage all organizations
        }

        if ($user->hasRole('admin')) {
            return $operation === 'read'; // Admin can only view their organization
        }

        return false;
    }

    /**
     * Get user's effective permissions
     */
    public function getUserPermissions(User $user): array
    {
        $permissions = [];

        if ($user->hasRole('super-admin')) {
            $permissions = [
                'access_all_organizations' => true,
                'manage_all_tournaments' => true,
                'manage_all_teams' => true,
                'manage_all_players' => true,
                'manage_system_settings' => true,
                'view_system_analytics' => true,
            ];
        } elseif ($user->hasRole('admin')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'manage_tournaments' => true,
                'manage_teams' => true,
                'manage_players' => true,
                'view_organization_analytics' => true,
            ];
        } elseif ($user->hasRole('tournament-director')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'manage_tournaments' => true,
                'view_teams' => true,
                'view_players' => true,
            ];
        } elseif ($user->hasRole('team-manager')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'manage_teams' => true,
                'manage_players' => true,
                'view_tournaments' => true,
            ];
        } elseif ($user->hasRole('coach')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'view_teams' => true,
                'view_players' => true,
                'view_tournaments' => true,
            ];
        } elseif ($user->hasRole('player')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'view_own_profile' => true,
                'view_teams' => true,
                'view_tournaments' => true,
            ];
        } elseif ($user->hasRole('parent')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'view_children' => true,
                'view_teams' => true,
                'view_tournaments' => true,
            ];
        } elseif ($user->hasRole('scout')) {
            $permissions = [
                'access_organization' => $user->organization_id,
                'view_players' => true,
                'view_teams' => true,
                'view_tournaments' => true,
            ];
        }

        return $permissions;
    }

    /**
     * Log permission check for auditing
     */
    public function logPermissionCheck(User $user, string $action, $resource = null, bool $granted = false): void
    {
        Log::info('Permission Check', [
            'user_id' => $user->id,
            'user_role' => $user->roles->pluck('name'),
            'user_organization' => $user->organization_id,
            'action' => $action,
            'resource' => $resource,
            'granted' => $granted,
            'timestamp' => now(),
        ]);
    }
}
