<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentTeamAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Role constants
    const ROLE_COACH = 'coach';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';

    /**
     * Get the team this admin belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user who is the admin.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this admin is the primary contact.
     */
    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    /**
     * Check if admin has specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get all available roles.
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_COACH => 'Coach',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_ADMIN => 'Team Admin',
        ];
    }

    /**
     * Scope to get primary admin for a team.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter by role.
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
