<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_id',
        'pool_id',
        'team_name',
        'team_contact_name',
        'team_contact_email',
        'team_contact_phone',
        'approval_status',
        'rejection_reason',
        'correction_notes',
        'approved_by',
        'approved_at',
        'registration_date',
        'seed_number',
        'pool_position',
        // Location fields (based on tournament's organization level)
        'country',
        'county',
        'sub_county',
        'ward',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'registration_date' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CORRECTION_REQUESTED = 'correction_requested';

    // Relationships
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(TournamentPool::class, 'pool_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function squads(): HasMany
    {
        return $this->hasMany(TournamentSquad::class, 'tournament_team_id');
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'away_team_id');
    }

    public function standing(): BelongsTo
    {
        return $this->belongsTo(TournamentStanding::class, 'id', 'tournament_team_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('approval_status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', self::STATUS_REJECTED);
    }

    public function scopeInPool($query, $poolId)
    {
        return $query->where('pool_id', $poolId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('pool_position', 'asc')->orderBy('seed_number', 'asc');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->approval_status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->approval_status === self::STATUS_REJECTED;
    }

    public function isCorrectionRequested(): bool
    {
        return $this->approval_status === self::STATUS_CORRECTION_REQUESTED;
    }

    public function getSquadCount(): int
    {
        return $this->squads()->count();
    }

    public function getVerifiedSquadCount(): int
    {
        return $this->squads()->where('verification_status', 'verified')->count();
    }

    public function canEditSquad(): bool
    {
        // Can edit if tournament is open and squad is not locked
        return $this->tournament->canRegister() && !$this->isSquadLocked();
    }

    public function isSquadLocked(): bool
    {
        // Check individual squad locks first
        if ($this->squads()->where('is_locked', true)->exists()) {
            return true;
        }
        // Check tournament-level lock
        return $this->tournament->isLocked();
    }

    public function meetsMinimumRequirement(): bool
    {
        return $this->getVerifiedSquadCount() >= $this->tournament->min_players;
    }

    public function meetsSquadLimit(): bool
    {
        return $this->getSquadCount() <= $this->tournament->squad_limit;
    }

    public function approve(User $approver): void
    {
        $this->update([
            'approval_status' => self::STATUS_APPROVED,
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    public function reject(User $approver, string $reason): void
    {
        $this->update([
            'approval_status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    public function requestCorrection(User $approver, string $notes): void
    {
        $this->update([
            'approval_status' => self::STATUS_CORRECTION_REQUESTED,
            'correction_notes' => $notes,
            'approved_by' => $approver->id,
        ]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->approval_status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CORRECTION_REQUESTED => 'info',
            default => 'secondary'
        };
    }

    // ELO Rating Methods
    /**
     * Get current ELO rating (or default)
     */
    public function getEloRating(): int
    {
        return $this->elo_rating ?? 1500;
    }

    /**
     * Get ELO rating display with rank indicator
     */
    public function getEloRatingDisplay(): string
    {
        $rating = $this->getEloRating();
        $rank = $this->getEloRank();
        return "{$rating} ({$rank})";
    }

    /**
     * Get ELO rank based on rating
     */
    public function getEloRank(): string
    {
        $rating = $this->getEloRating();
        return match(true) {
            $rating >= 2400 => 'Elite',
            $rating >= 2200 => 'Master',
            $rating >= 2000 => 'Expert',
            $rating >= 1800 => 'Advanced',
            $rating >= 1600 => 'Intermediate',
            $rating >= 1400 => 'Developing',
            default => 'Beginner'
        };
    }

    /**
     * Get ELO rank color class
     */
    public function getEloRankColor(): string
    {
        $rating = $this->getEloRating();
        return match(true) {
            $rating >= 2400 => 'danger',
            $rating >= 2200 => 'warning',
            $rating >= 2000 => 'success',
            $rating >= 1800 => 'info',
            $rating >= 1600 => 'primary',
            $rating >= 1400 => 'secondary',
            default => 'light'
        };
    }

    /**
     * Get last ELO change display
     */
    public function getLastEloChangeDisplay(): string
    {
        $change = $this->last_elo_change ?? 0;
        if ($change > 0) {
            return '+' . $change;
        }
        return (string)$change;
    }

    /**
     * Get win rate percentage
     */
    public function getWinRate(): float
    {
        $matches = $this->elo_matches ?? 0;
        if ($matches === 0) {
            return 0;
        }
        $wins = $this->wins ?? 0;
        return round(($wins / $matches) * 100, 1);
    }

    /**
     * Get goal difference
     */
    public function getGoalDifference(): int
    {
        return ($this->goals_for ?? 0) - ($this->goals_against ?? 0);
    }

    /**
     * Check if team has enough matches for reliable ELO
     */
    public function hasReliableElo(): bool
    {
        return ($this->elo_matches ?? 0) >= 5;
    }

    // ==================== Location Methods ====================

    /**
     * Get location fields based on tournament's organization level
     */
    public function getLocationFields(): array
    {
        $organization = $this->tournament->organization ?? null;

        if (!$organization) {
            return ['country']; // Default
        }

        return $organization->getLocationFields();
    }

    /**
     * Get location level from tournament's organization
     */
    public function getLocationLevel(): string
    {
        $organization = $this->tournament->organization ?? null;

        if (!$organization) {
            return 'country'; // Default
        }

        return $organization->getEffectiveLocationLevel();
    }

    /**
     * Get location display string
     */
    public function getLocationDisplay(): string
    {
        $parts = [];

        if ($this->country) {
            $parts[] = \App\Models\Organization::COUNTRIES[$this->country] ?? $this->country;
        }
        if ($this->county) {
            $parts[] = $this->county;
        }
        if ($this->sub_county) {
            $parts[] = $this->sub_county;
        }
        if ($this->ward) {
            $parts[] = $this->ward;
        }

        return implode(' > ', $parts);
    }

    /**
     * Get display name for the team (handles both linked and independent teams)
     */
    public function getDisplayNameAttribute(): string
    {
        // First try linked team name, then fallback to independent team_name
        return $this->team->name ?? $this->team_name ?? 'Unknown Team';
    }
}
