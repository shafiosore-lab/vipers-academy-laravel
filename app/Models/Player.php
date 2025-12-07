<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'category',
        'position',
        'age',
        'jersey_number',
        'image_path',
        'bio',
        'goals',
        'assists',
        'appearances',
        'yellow_cards',
        'red_cards',
        'program_id',
        'registration_status',
        'approval_type',
        'documents_completed',
        'temporary_approval_granted_at',
        'temporary_approval_expires_at',
        'temporary_approval_notes',
        'partner_id',
        'email'
    ];

    protected $casts = [
        'temporary_approval_granted_at' => 'datetime',
        'temporary_approval_expires_at' => 'datetime',
    ];

    const POSITION_ORDER = [
        'goalkeeper' => 1,
        'defender' => 2,
        'midfielder' => 3,
        'striker' => 4
    ];

    const CATEGORY_ORDER = [
        'under-13' => 1,
        'under-15' => 2,
        'under-17' => 3,
        'senior' => 4
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function gameStatistics()
    {
        return $this->hasMany(GameStatistic::class);
    }

    // ==========================================
    // ATTRIBUTES & ACCESSORS
    // ==========================================

    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        return $this->name;
    }

    public function getPositionOrderAttribute()
    {
        return self::POSITION_ORDER[strtolower($this->position)] ?? 999;
    }

    public function getCategoryOrderAttribute()
    {
        return self::CATEGORY_ORDER[strtolower($this->category)] ?? 999;
    }

    public function getFormattedCategoryAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }

    public function getFormattedPositionAttribute()
    {
        return ucfirst($this->position);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('assets/img/players/' . $this->image_path))) {
            return asset('assets/img/players/' . $this->image_path);
        }
        return asset('assets/img/default-player.png');
    }

    // ==========================================
    // QUERY SCOPES
    // ==========================================

    public function scopeOrderedByPositionAndAge($query)
    {
        return $query->orderByRaw("
            CASE LOWER(position)
                WHEN 'goalkeeper' THEN 1
                WHEN 'defender' THEN 2
                WHEN 'midfielder' THEN 3
                WHEN 'striker' THEN 4
                ELSE 999
            END
        ")->orderBy('age', 'asc');
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('approval_type', ['full', 'temporary']);
    }

    public function scopeFullyApproved($query)
    {
        return $query->where('approval_type', 'full');
    }

    // ==========================================
    // APPROVAL CHECKS
    // ==========================================

    public function isApproved()
    {
        return in_array($this->approval_type, ['full', 'temporary']);
    }

    public function isFullyApproved()
    {
        return $this->approval_type === 'full';
    }

    public function isTemporarilyApproved()
    {
        return $this->approval_type === 'temporary';
    }

    public function hasTemporaryApproval()
    {
        return $this->isTemporarilyApproved();
    }

    public function hasFullApproval()
    {
        return $this->isFullyApproved();
    }

    public function isTemporaryApprovalValid()
    {
        if ($this->approval_type !== 'temporary' || !$this->temporary_approval_expires_at) {
            return false;
        }

        return now()->lessThanOrEqualTo($this->temporary_approval_expires_at);
    }

    public function isTemporaryApprovalExpired()
    {
        if ($this->approval_type !== 'temporary' || !$this->temporary_approval_expires_at) {
            return false;
        }

        return now()->greaterThan($this->temporary_approval_expires_at);
    }

    public function getTemporaryApprovalDaysRemaining()
    {
        if ($this->approval_type !== 'temporary' || !$this->temporary_approval_expires_at) {
            return 0;
        }

        $remaining = now()->diffInDays($this->temporary_approval_expires_at, false);
        return max(0, $remaining);
    }

    // ==========================================
    // APPROVAL MANAGEMENT
    // ==========================================

    public function grantFullApproval()
    {
        $this->update([
            'approval_type' => 'full',
            'temporary_approval_granted_at' => null,
            'temporary_approval_expires_at' => null,
            'temporary_approval_notes' => null,
        ]);

        return $this;
    }

    public function grantTemporaryApproval($notes = null)
    {
        $expiryDate = now()->addDays(7);

        $this->update([
            'approval_type' => 'temporary',
            'temporary_approval_granted_at' => now(),
            'temporary_approval_expires_at' => $expiryDate,
            'temporary_approval_notes' => $notes,
        ]);

        return $this;
    }

    public function revokeApproval()
    {
        $this->update([
            'approval_type' => 'none',
            'temporary_approval_granted_at' => null,
            'temporary_approval_expires_at' => null,
            'temporary_approval_notes' => null,
        ]);

        return $this;
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    public function canAccessPortal()
    {
        return $this->isApproved() && $this->isTemporaryApprovalValid();
    }

    public function needsDocumentCompletion()
    {
        return !$this->documents_completed;
    }

    public function getTotalGoals()
    {
        return $this->goals + ($this->gameStatistics->sum('goals_scored') ?? 0);
    }

    public function getTotalAssists()
    {
        return $this->assists + ($this->gameStatistics->sum('assists') ?? 0);
    }
}
