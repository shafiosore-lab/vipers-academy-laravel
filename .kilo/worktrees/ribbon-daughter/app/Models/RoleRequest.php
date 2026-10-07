<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_role_id',
        'approved_by',
        'organization_id',
        'status',
        'reason',
        'approval_notes',
        'requested_at',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user who requested the role.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the requested role.
     */
    public function requestedRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'requested_role_id');
    }

    /**
     * Get the user who approved/rejected the request.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope to get pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get requests for an organization.
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Approve the request.
     */
    public function approve(User $approver, string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $approver->id,
            'approval_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        // Assign the role to the user
        $user = $this->user;
        $role = $this->requestedRole;

        if ($user && $role) {
            $hierarchyService = new \App\Services\RoleHierarchyService();
            $hierarchyService->assignBaseRoleWithSpecialization($user, [$role->slug]);

            // Log the role assignment
            RoleAuditLog::logRoleAssignment($approver, $user, $role, $this->organization);
        }
    }

    /**
     * Reject the request.
     */
    public function reject(User $reviewer, string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $reviewer->id,
            'approval_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Cancel the request.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Check if the request is still pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            default => 'secondary',
        };
    }
}
