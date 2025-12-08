<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
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
     * Get all permissions for this role (including through relationships).
     */
    public function getAllPermissions()
    {
        return $this->permissions;
    }
}
