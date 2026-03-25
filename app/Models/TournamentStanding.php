<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentStanding extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'tournament_team_id',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
        'position',
    ];

    protected $casts = [
        'played' => 'integer',
        'won' => 'integer',
        'drawn' => 'integer',
        'lost' => 'integer',
        'goals_for' => 'integer',
        'goals_against' => 'integer',
        'goal_difference' => 'integer',
        'points' => 'integer',
        'position' => 'integer',
    ];

    // Relationships
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'tournament_team_id');
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }

    public function scopeByTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    // Helper methods
    public function getFormDisplay(): string
    {
        // This could be extended to show recent match results
        return '';
    }

    public function getGoalDifferenceDisplay(): string
    {
        $diff = $this->goal_difference;
        return $diff > 0 ? "+{$diff}" : (string)$diff;
    }
}
