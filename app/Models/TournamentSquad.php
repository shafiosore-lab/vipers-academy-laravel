<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentSquad extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_team_id',
        'player_id',
        'jersey_number',
        'position',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at',
        'registration_date',
        'is_locked',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'registration_date' => 'datetime',
        'is_locked' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function tournamentTeam(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'tournament_team_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('verification_status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', self::STATUS_VERIFIED);
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', self::STATUS_REJECTED);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->verification_status === self::STATUS_VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }

    public function isLocked(): bool
    {
        return $this->is_locked || $this->tournamentTeam->isSquadLocked();
    }

    public function canEdit(): bool
    {
        return !$this->isLocked();
    }

    public function verify(User $verifier, string $notes = null): void
    {
        $this->update([
            'verification_status' => self::STATUS_VERIFIED,
            'verification_notes' => $notes,
            'verified_by' => $verifier->id,
            'verified_at' => now(),
        ]);
    }

    public function reject(User $verifier, string $reason): void
    {
        $this->update([
            'verification_status' => self::STATUS_REJECTED,
            'verification_notes' => $reason,
            'verified_by' => $verifier->id,
            'verified_at' => now(),
        ]);
    }

    public function lock(): void
    {
        $this->update(['is_locked' => true]);
    }

    public function unlock(): void
    {
        $this->update(['is_locked' => false]);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->verification_status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_VERIFIED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary'
        };
    }

    // Check if player is registered in another team in the same tournament
    public static function playerRegisteredInTournament(int $playerId, int $tournamentId, int $excludeTeamId = null): bool
    {
        $query = self::whereHas('tournamentTeam', function ($q) use ($tournamentId) {
            $q->where('tournament_id', $tournamentId);
        })->where('player_id', $playerId);

        if ($excludeTeamId) {
            $query->where('tournament_team_id', '!=', $excludeTeamId);
        }

        return $query->exists();
    }
}
