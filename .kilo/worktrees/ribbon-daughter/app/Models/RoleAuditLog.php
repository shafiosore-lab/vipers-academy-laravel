<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class RoleAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_user_id',
        'role_id',
        'organization_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    const ACTION_ROLE_ASSIGNED = 'role_assigned';
    const ACTION_ROLE_REMOVED = 'role_removed';
    const ACTION_PERMISSION_CHANGED = 'permission_changed';
    const ACTION_ROLE_CREATED = 'role_created';
    const ACTION_ROLE_UPDATED = 'role_updated';
    const ACTION_ROLE_DELETED = 'role_deleted';
    const ACTION_ROLE_REQUESTED = 'role_requested';
    const ACTION_ROLE_REQUEST_APPROVED = 'role_request_approved';
    const ACTION_ROLE_REQUEST_REJECTED = 'role_request_rejected';
    const ACTION_HYBRID_ROLE_CREATED = 'hybrid_role_created';
    const ACTION_INHERITANCE_CHANGED = 'inheritance_changed';

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the target user (if applicable).
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Get the role (if applicable).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Log a role assignment.
     */
    public static function logRoleAssignment(User $performedBy, User $targetUser, Role $role, ?Organization $organization = null): self
    {
        return self::create([
            'user_id' => $performedBy->id,
            'target_user_id' => $targetUser->id,
            'role_id' => $role->id,
            'organization_id' => $organization?->id,
            'action' => self::ACTION_ROLE_ASSIGNED,
            'new_values' => [
                'role_name' => $role->name,
                'role_slug' => $role->slug,
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a role removal.
     */
    public static function logRoleRemoval(User $performedBy, User $targetUser, Role $role, ?Organization $organization = null): self
    {
        return self::create([
            'user_id' => $performedBy->id,
            'target_user_id' => $targetUser->id,
            'role_id' => $role->id,
            'organization_id' => $organization?->id,
            'action' => self::ACTION_ROLE_REMOVED,
            'old_values' => [
                'role_name' => $role->name,
                'role_slug' => $role->slug,
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a permission change.
     */
    public static function logPermissionChange(User $performedBy, Role $role, array $oldPermissions, array $newPermissions, ?Organization $organization = null): self
    {
        return self::create([
            'user_id' => $performedBy->id,
            'role_id' => $role->id,
            'organization_id' => $organization?->id,
            'action' => self::ACTION_PERMISSION_CHANGED,
            'old_values' => ['permissions' => $oldPermissions],
            'new_values' => ['permissions' => $newPermissions],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log role creation.
     */
    public static function logRoleCreated(User $performedBy, Role $role, ?Organization $organization = null): self
    {
        return self::create([
            'user_id' => $performedBy->id,
            'role_id' => $role->id,
            'organization_id' => $organization?->id,
            'action' => self::ACTION_ROLE_CREATED,
            'new_values' => [
                'role_name' => $role->name,
                'role_slug' => $role->slug,
                'is_template' => $role->is_template,
                'parent_role_id' => $role->parent_role_id,
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log role request.
     */
    public static function logRoleRequested(User $requestedBy, Role $role, string $reason, ?Organization $organization = null): self
    {
        return self::create([
            'user_id' => $requestedBy->id,
            'target_user_id' => $requestedBy->id,
            'role_id' => $role->id,
            'organization_id' => $organization?->id,
            'action' => self::ACTION_ROLE_REQUESTED,
            'new_values' => [
                'role_name' => $role->name,
                'reason' => $reason,
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get action badge class.
     */
    public function getActionBadgeClassAttribute(): string
    {
        return match($this->action) {
            self::ACTION_ROLE_ASSIGNED => 'success',
            self::ACTION_ROLE_REMOVED => 'danger',
            self::ACTION_PERMISSION_CHANGED => 'warning',
            self::ACTION_ROLE_CREATED => 'info',
            self::ACTION_ROLE_UPDATED => 'primary',
            self::ACTION_ROLE_DELETED => 'dark',
            self::ACTION_ROLE_REQUESTED => 'info',
            self::ACTION_ROLE_REQUEST_APPROVED => 'success',
            self::ACTION_ROLE_REQUEST_REJECTED => 'danger',
            self::ACTION_HYBRID_ROLE_CREATED => 'purple',
            self::ACTION_INHERITANCE_CHANGED => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get action display name.
     */
    public function getActionDisplayNameAttribute(): string
    {
        return match($this->action) {
            self::ACTION_ROLE_ASSIGNED => 'Role Assigned',
            self::ACTION_ROLE_REMOVED => 'Role Removed',
            self::ACTION_PERMISSION_CHANGED => 'Permissions Changed',
            self::ACTION_ROLE_CREATED => 'Role Created',
            self::ACTION_ROLE_UPDATED => 'Role Updated',
            self::ACTION_ROLE_DELETED => 'Role Deleted',
            self::ACTION_ROLE_REQUESTED => 'Role Requested',
            self::ACTION_ROLE_REQUEST_APPROVED => 'Request Approved',
            self::ACTION_ROLE_REQUEST_REJECTED => 'Request Rejected',
            self::ACTION_HYBRID_ROLE_CREATED => 'Hybrid Role Created',
            self::ACTION_INHERITANCE_CHANGED => 'Inheritance Changed',
            default => $this->action,
        };
    }
}
