<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'type',
        'opponent',
        'match_date',
        'venue',
        'status',
        'vipers_score',
        'opponent_score',
        'description',
        'tournament_name',
        'images',
        'live_link',
        'highlights_link',
        'match_summary',
        'registration_open',
        'registration_deadline',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'images' => 'array',
        'registration_open' => 'boolean',
        'registration_deadline' => 'date',
        'vipers_score' => 'integer',
        'opponent_score' => 'integer',
    ];

    // Scopes for different match types
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('match_date', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeFriendlies($query)
    {
        return $query->where('type', 'friendly');
    }

    public function scopeTournaments($query)
    {
        return $query->where('type', 'tournament');
    }
}
