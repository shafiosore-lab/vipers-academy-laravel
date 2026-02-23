<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'player_id',
        'team_id',
        'staff_id',
        'quantity',
        'assigned_by',
        'assigned_date',
        'returned_date',
        'condition_when_assigned',
        'condition_when_returned',
        'status',
        'notes',
        'organization_id',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date',
        'quantity' => 'integer',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_RETURNED = 'returned';
    const STATUS_LOST = 'lost';
    const STATUS_DAMAGED = 'damaged';

    /**
     * Get the equipment being distributed
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Get the player this equipment was assigned to
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get the team this equipment was assigned to
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the staff member this equipment was assigned to
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who assigned this equipment
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope for active distributions
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for returned equipment
     */
    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    /**
     * Scope for a specific player
     */
    public function scopeForPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    /**
     * Scope for a specific team
     */
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_LOST => 'Lost',
            self::STATUS_DAMAGED => 'Damaged',
        ];
    }

    /**
     * Check if distribution is still active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Mark as returned
     */
    public function markAsReturned($condition = null)
    {
        $this->update([
            'status' => self::STATUS_RETURNED,
            'returned_date' => now(),
            'condition_when_returned' => $condition ?? $this->condition_when_assigned,
        ]);
    }

    /**
     * Mark as lost
     */
    public function markAsLost()
    {
        $this->update([
            'status' => self::STATUS_LOST,
            'returned_date' => now(),
        ]);
    }

    /**
     * Mark as damaged
     */
    public function markAsDamaged($condition = null)
    {
        $this->update([
            'status' => self::STATUS_DAMAGED,
            'returned_date' => now(),
            'condition_when_returned' => $condition,
        ]);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'primary',
            self::STATUS_RETURNED => 'success',
            self::STATUS_LOST => 'danger',
            self::STATUS_DAMAGED => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get assigned to name
     */
    public function getAssignedToNameAttribute()
    {
        if ($this->player) {
            return $this->player->full_name;
        }
        if ($this->team) {
            return $this->team->name;
        }
        if ($this->staff) {
            return $this->staff->name ?? 'Staff Member';
        }
        return 'Unknown';
    }

    /**
     * Get assigned to type
     */
    public function getAssignedToTypeAttribute()
    {
        if ($this->player) {
            return 'Player';
        }
        if ($this->team) {
            return 'Team';
        }
        if ($this->staff) {
            return 'Staff';
        }
        return 'Unknown';
    }
}
