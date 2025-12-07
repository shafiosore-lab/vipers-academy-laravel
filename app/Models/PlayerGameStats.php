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
        // Advanced position-specific stats
        'shots',
        'passes_attempted',
        'aerial_duels_won',
        'clearances',
        'blocks',
        'crosses_attempted',
        'crosses_completed',
        'dribbles_completed',
        'progressive_runs',
        'defensive_duels_won',
        'ball_recoveries',
        'progressive_passes',
        'duels_won',
        'expected_assists',
        'ball_progressions',
        'expected_goals',
        'chances_created',
        'through_balls',
        'passes_into_final_third',
        'touches_in_box',
        'big_chances_scored',
        'big_chances_missed',
        'hold_up_play_success',
        'chance_creation',
        'goals_conceded',
        'high_claims',
        'punches',
        'long_pass_accuracy',
        'short_pass_accuracy',
        'sweeper_actions',
        'penalties_faced',
        'penalties_saved',
        'forward_passes',
        'key_passes',
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
