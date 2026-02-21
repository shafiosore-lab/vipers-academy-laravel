<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoleTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
        'organization_id',
        'role_configurations',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'role_configurations' => 'array',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the organization this template belongs to.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope to get public templates.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true)->where('is_active', true);
    }

    /**
     * Scope to get templates for an organization.
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where(function ($q) use ($organizationId) {
            $q->where('is_public', true)
              ->orWhere('organization_id', $organizationId);
        })->where('is_active', true);
    }

    /**
     * Apply this template to create roles for an organization.
     */
    public function applyToOrganization(Organization $organization): array
    {
        $createdRoles = [];
        $config = $this->role_configurations ?? [];

        foreach ($config as $roleConfig) {
            $role = Role::create([
                'name' => $roleConfig['name'],
                'slug' => $roleConfig['slug'] . '-' . $organization->id,
                'description' => $roleConfig['description'] ?? null,
                'type' => $roleConfig['type'] ?? 'custom',
                'is_template' => false,
                'organization_id' => $organization->id,
                'is_active' => true,
                'module' => $roleConfig['module'] ?? null,
                'department' => $roleConfig['department'] ?? null,
            ]);

            // Assign permissions if specified
            if (isset($roleConfig['permissions'])) {
                $permissions = Permission::whereIn('slug', $roleConfig['permissions'])->get();
                $role->permissions()->sync($permissions);
            }

            $createdRoles[] = $role;
        }

        return $createdRoles;
    }

    /**
     * Get the display name for the template.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->organization ? " ({$this->organization->name})" : '');
    }
}
