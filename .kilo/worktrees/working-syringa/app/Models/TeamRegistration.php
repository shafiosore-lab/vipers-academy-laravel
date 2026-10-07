<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'team_city',
        'team_description',
        'status',
        'rejection_reason',
        'notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the tournament this registration is for.
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the user who reviewed this registration.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if registration is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if registration is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if registration is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approve the registration.
     */
    public function approve(User $reviewer, string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Reject the registration.
     */
    public function reject(User $reviewer, string $reason): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Scope to get pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get approved registrations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope to filter by tournament.
     */
    public function scopeForTournament($query, int $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }
}
