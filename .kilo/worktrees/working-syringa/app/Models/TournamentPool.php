<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TournamentPool Model
 *
 * Represents a pool/division within a tournament for team分组管理
 * Supports drag-and-drop team assignment and automatic redistribution
 */
class TournamentPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
        'description',
        'position',
        'seed_method',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    // Seed method constants
    const SEED_MANUAL = 'manual';
    const SEED_RANDOM = 'random';
    const SEED_SEEDING = 'seeding';
    const SEED_PERFORMANCE = 'performance';

    /**
     * Get the tournament this pool belongs to
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get teams assigned to this pool
     */
    public function teams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class, 'pool_id');
    }

    /**
     * Get matches within this pool
     */
    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'pool_id');
    }

    /**
     * Scope for ordered pools
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }

    /**
     * Scope for a specific tournament
     */
    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    /**
     * Get teams ordered by seed/position within pool
     */
    public function getOrderedTeams()
    {
        return $this->teams()
            ->ordered()
            ->get()
            ->sortBy('pool_position');
    }

    /**
     * Get pool standing/leaderboard
     */
    public function getStandings()
    {
        $teams = $this->teams()->with(['standing'])->get();

        return $teams->map(function ($team) {
            $standing = $team->standing;
            return [
                'team' => $team,
                'position' => $team->pool_position ?? 0,
                'played' => $standing?->played ?? 0,
                'won' => $standing?->won ?? 0,
                'drawn' => $standing?->drawn ?? 0,
                'lost' => $standing?->lost ?? 0,
                'points' => $standing?->points ?? 0,
                'goal_difference' => $standing?->goal_difference ?? 0,
            ];
        })->sortByDesc('points');
    }

    /**
     * Automatically redistribute teams to pools based on selected method
     */
    public static function redistributeTeams($tournament, $numPools, $method = self::SEED_MANUAL)
    {
        $teams = $tournament->approvedTeams()->with('standing')->get();

        if ($teams->count() === 0) {
            return;
        }

        // Create pools if they don't exist
        $pools = [];
        for ($i = 1; $i <= $numPools; $i++) {
            $pool = $tournament->pools()->updateOrCreate(
                ['name' => 'Pool ' . chr(64 + $i)],
                ['position' => $i, 'seed_method' => $method]
            );
            $pools[] = $pool;
        }

        // Distribute teams based on method
        switch ($method) {
            case self::SEED_RANDOM:
                $teams = $teams->shuffle();
                break;

            case self::SEED_SEEDING:
                $teams = $teams->sortByDesc('seed_number');
                break;

            case self::SEED_PERFORMANCE:
                $teams = $teams->sortByDesc(function ($team) {
                    return $team->standing?->points ?? 0;
                });
                break;

            case self::SEED_MANUAL:
            default:
                // Keep existing order or use pool_position
                $teams = $teams->sortBy('pool_position');
                break;
        }

        // Distribute teams round-robin style
        $teamArray = $teams->values();
        $poolIndex = 0;
        $positionInPool = 1;

        foreach ($teamArray as $index => $team) {
            $team->update([
                'pool_id' => $pools[$poolIndex]->id,
                'pool_position' => $positionInPool,
            ]);

            $poolIndex = ($poolIndex + 1) % $numPools;
            if ($poolIndex === 0) {
                $positionInPool++;
            }
        }
    }

    /**
     * Get the next available seed number for this pool
     */
    public function getNextSeedNumber(): int
    {
        $maxSeed = $this->teams()->max('seed_number');
        return ($maxSeed ?? 0) + 1;
    }

    /**
     * Get display name with team count
     */
    public function getDisplayName(): string
    {
        $count = $this->teams()->count();
        return "{$this->name} ({$count} teams)";
    }
}
