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
        // Card statistics
        'yellow_cards',
        'red_cards',
        // Clean sheets
        'clean_sheets',
        // Home/Away breakdown
        'home_played',
        'home_won',
        'home_drawn',
        'home_lost',
        'home_goals_for',
        'home_goals_against',
        'away_played',
        'away_won',
        'away_drawn',
        'away_lost',
        'away_goals_for',
        'away_goals_against',
        // Recent form (JSON array of last 5 results)
        'form',
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
        // Card statistics
        'yellow_cards' => 'integer',
        'red_cards' => 'integer',
        // Clean sheets
        'clean_sheets' => 'integer',
        // Home/Away breakdown
        'home_played' => 'integer',
        'home_won' => 'integer',
        'home_drawn' => 'integer',
        'home_lost' => 'integer',
        'home_goals_for' => 'integer',
        'home_goals_against' => 'integer',
        'away_played' => 'integer',
        'away_won' => 'integer',
        'away_drawn' => 'integer',
        'away_lost' => 'integer',
        'away_goals_for' => 'integer',
        'away_goals_against' => 'integer',
        // Form is JSON array
        'form' => 'array',
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
    public function getGoalDifferenceDisplay(): string
    {
        $diff = $this->goal_difference;
        return $diff > 0 ? "+{$diff}" : (string)$diff;
    }

    /**
     * Get win percentage
     */
    public function getWinPercentage(): float
    {
        if ($this->played === 0) {
            return 0;
        }
        return round(($this->won / $this->played) * 100, 1);
    }

    /**
     * Get goals per match average
     */
    public function getGoalsPerMatch(): float
    {
        if ($this->played === 0) {
            return 0;
        }
        return round($this->goals_for / $this->played, 2);
    }

    /**
     * Get goals conceded per match average
     */
    public function getGoalsConcededPerMatch(): float
    {
        if ($this->played === 0) {
            return 0;
        }
        return round($this->goals_against / $this->played, 2);
    }

    /**
     * Get form display (last 5 matches)
     */
    public function getFormDisplay(): string
    {
        $form = $this->form ?? [];
        if (empty($form)) {
            return '-';
        }

        $html = '<div class="d-flex gap-1">';
        foreach ($form as $result) {
            $color = match($result) {
                'W' => 'success',
                'D' => 'warning',
                'L' => 'danger',
                default => 'secondary'
            };
            $html .= "<span class=\"badge bg-{$color}\">{$result}</span>";
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Get form as simple string (W/D/L)
     */
    public function getFormString(): string
    {
        $form = $this->form ?? [];
        return implode('', $form);
    }

    /**
     * Check if team has clean sheet in a match (conceded 0)
     */
    public function hasCleanSheet(int $goalsConceded): bool
    {
        return $goalsConceded === 0;
    }

    /**
     * Get points per game
     */
    public function getPointsPerGame(): float
    {
        if ($this->played === 0) {
            return 0;
        }
        return round($this->points / $this->played, 2);
    }
}
