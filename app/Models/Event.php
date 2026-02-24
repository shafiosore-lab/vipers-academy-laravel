<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'type',
        'event_date',
        'status',
        'home_team_id',
        'away_team_id',
        'venue',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope('organization', function ($query) {
            $organizationId = auth()->check() ? auth()->user()->organization_id : null;
            if ($organizationId) {
                $query->where('organization_id', $organizationId);
            }
        });
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes
    public function scopeOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('event_date', [$from, $to]);
    }

    // Helper methods
    public static function getTypes(): array
    {
        return [
            'match' => 'Match',
            'tournament' => 'Tournament',
            'training_camp' => 'Training Camp',
            'social' => 'Social Event',
            'other' => 'Other',
        ];
    }

    public function getMatchTitle(): string
    {
        if ($this->type === 'match' && $this->homeTeam && $this->awayTeam) {
            return "{$this->homeTeam->name} vs {$this->awayTeam->name}";
        }
        return $this->name;
    }

    public function isHomeTeam(Team $team): bool
    {
        return $this->home_team_id === $team->id;
    }

    public function getOpponent(Team $team): ?Team
    {
        if ($this->home_team_id === $team->id) {
            return $this->awayTeam;
        }
        if ($this->away_team_id === $team->id) {
            return $this->homeTeam;
        }
        return null;
    }
}
