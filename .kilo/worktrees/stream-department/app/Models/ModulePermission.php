<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ModulePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'name',
        'description',
        'actions',
        'is_active',
    ];

    protected $casts = [
        'actions' => 'array',
        'is_active' => 'boolean',
    ];

    // Default module actions
    const ACTIONS = [
        'create' => 'Create',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete',
        'export' => 'Export',
        'import' => 'Import',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'manage' => 'Manage',
        'view' => 'View',
    ];

    // System modules
    const MODULES = [
        'players' => 'Players Management',
        'staff' => 'Staff Management',
        'finance' => 'Finance Management',
        'attendance' => 'Attendance',
        'training' => 'Training Sessions',
        'matches' => 'Matches',
        'reports' => 'Reports',
        'documents' => 'Documents',
        'settings' => 'Settings',
        'users' => 'User Management',
        'roles' => 'Role Management',
        'organizations' => 'Organizations',
        'subscriptions' => 'Subscriptions',
        'blog' => 'Blog & Media',
        'gallery' => 'Gallery',
        'payments' => 'Payments',
        'programs' => 'Programs',
        ' standings' => 'Standings',
        'jobs' => 'Careers & Jobs',
    ];

    /**
     * Get the roles that have this module permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_module_permissions')
            ->withPivot('allowed_actions', 'is_inherited')
            ->withTimestamps();
    }

    /**
     * Check if a specific action is allowed.
     */
    public function hasAction(string $action): bool
    {
        $actions = $this->actions ?? [];
        return in_array($action, $actions);
    }

    /**
     * Get all available actions for this module.
     */
    public function getAvailableActionsAttribute(): array
    {
        return $this->actions ?? array_keys(self::ACTIONS);
    }

    /**
     * Scope to get active modules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get display name for module.
     */
    public function getDisplayNameAttribute(): string
    {
        return self::MODULES[$this->module] ?? $this->name;
    }

    /**
     * Get formatted actions for display.
     */
    public function getFormattedActionsAttribute(): string
    {
        $actions = $this->actions ?? [];
        return implode(', ', array_map(function($action) {
            return self::ACTIONS[$action] ?? $action;
        }, $actions));
    }
}
