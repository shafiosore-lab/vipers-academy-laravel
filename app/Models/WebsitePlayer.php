<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'category',
        'position',
        'age',
        'image_path',
        'jersey_number',
        'bio',
        'goals',
        'assists',
        'appearances',
        'yellow_cards',
        'red_cards',
        'youtube_url',
    ];

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get formatted category name
     */
    public function getFormattedCategoryAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }

    /**
     * Get formatted position name
     */
    public function getFormattedPositionAttribute()
    {
        return ucfirst($this->position);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('assets/img/players/' . $this->image_path))) {
            return asset('assets/img/players/' . $this->image_path);
        }
        return null;
    }

    /**
     * Get the game stats for this player.
     */
    public function gameStats()
    {
        return $this->hasMany(PlayerGameStats::class, 'player_id');
    }

    /**
     * Recalculate cumulative stats from game records
     */
    public function recalculateCumulativeStats()
    {
        $stats = $this->gameStats()
            ->selectRaw('
                SUM(goals_scored) as total_goals,
                SUM(assists) as total_assists,
                COUNT(*) as total_appearances,
                SUM(yellow_cards) as total_yellow_cards,
                SUM(red_cards) as total_red_cards
            ')
            ->first();

        $this->update([
            'goals' => $stats->total_goals ?? 0,
            'assists' => $stats->total_assists ?? 0,
            'appearances' => $stats->total_appearances ?? 0,
            'yellow_cards' => $stats->total_yellow_cards ?? 0,
            'red_cards' => $stats->total_red_cards ?? 0,
        ]);

        return $this;
    }
}
