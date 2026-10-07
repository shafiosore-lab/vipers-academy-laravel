<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'game_date',
        'opponent',
        'tournament',
        'goals_scored',
        'assists',
        'minutes_played',
        'shots_on_target',
        'passes_completed',
        'tackles',
        'interceptions',
        'saves',
        'yellow_cards',
        'red_cards',
        'rating',
        'game_summary',
        'ai_generated',
        'additional_stats',
    ];

    protected $casts = [
        'game_date' => 'date',
        'ai_generated' => 'boolean',
        'additional_stats' => 'array',
        'rating' => 'decimal:2',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Scope to get AI-generated statistics
     */
    public function scopeAiGenerated($query)
    {
        return $query->where('ai_generated', true);
    }

    /**
     * Scope to get manually entered statistics
     */
    public function scopeManuallyEntered($query)
    {
        return $query->where('ai_generated', false);
    }

    /**
     * Get total points for the game (goals + assists)
     */
    public function getTotalPointsAttribute()
    {
        return $this->goals_scored + $this->assists;
    }

    /**
     * Check if the player played the full game
     */
    public function playedFullGame()
    {
        return $this->minutes_played >= 90;
    }
}
