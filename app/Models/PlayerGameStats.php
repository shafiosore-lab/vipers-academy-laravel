<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerGameStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'game_date',
        'minutes_played',
        'opponent_team',
        'tournament',
        'goals_scored',
        'assists',
        'shots_on_target',
        'passes_completed',
        'tackles',
        'interceptions',
        'saves',
        'rating',
        'yellow_cards',
        'red_cards',
        'game_summary',
    ];

    protected $casts = [
        'game_date' => 'date',
        'rating' => 'decimal:1',
    ];

    /**
     * Get the player that owns the game stats.
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(WebsitePlayer::class, 'player_id');
    }
}
