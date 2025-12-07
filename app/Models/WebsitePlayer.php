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
        // Advanced position-specific stats
        'saves',
        'clean_sheets',
        'goals_conceded',
        'save_percentage',
        'distribution_accuracy',
        'aerial_duels_won',
        'clearances',
        'blocks',
        'tackles_won',
        'interceptions',
        'passing_accuracy',
        'ball_recoveries',
        'crosses_attempted',
        'cross_accuracy',
        'key_passes',
        'dribbles_completed',
        'progressive_runs',
        'defensive_duels_won',
        'progressive_passes',
        'duels_won',
        'expected_assists',
        'shots',
        'ball_progressions',
        'expected_goals',
        'chances_created',
        'shots_on_target',
        'through_balls',
        'passes_into_final_third',
        'shot_conversion_rate',
        'touches_in_box',
        'big_chances_scored',
        'big_chances_missed',
        'hold_up_play_success',
        'chance_creation',
        // Skills radar attributes
        'shot_stopping',
        'distribution',
        'aerial_ability',
        'command_area',
        'handling',
        'positioning',
        'tackling',
        'strength',
        'pace',
        'crossing',
        'dribbling',
        'stamina',
        'passing',
        'vision',
        'decisions',
        'finishing',
        'technique',
        'flair',
        'balance',
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
     * Recalculate cumulative stats from game records with position-specific metrics
     */
    public function recalculateCumulativeStats()
    {
        $gameStats = $this->gameStats()->get();

        // Basic cumulative stats
        $basicStats = [
            'goals' => $gameStats->sum('goals_scored'),
            'assists' => $gameStats->sum('assists'),
            'appearances' => $gameStats->count(),
            'yellow_cards' => $gameStats->sum('yellow_cards'),
            'red_cards' => $gameStats->sum('red_cards'),
        ];

        // Position-specific advanced statistics
        $advancedStats = $this->calculatePositionSpecificStats($gameStats);

        // Skills radar attributes (0-100 scale)
        $radarStats = $this->calculateSkillsRadar($gameStats, $advancedStats);

        // Update player with all calculated stats
        $this->update(array_merge($basicStats, $advancedStats, $radarStats));

        return $this;
    }

    /**
     * Calculate position-specific advanced statistics
     */
    private function calculatePositionSpecificStats($gameStats)
    {
        $position = strtoupper($this->position);

        switch ($position) {
            case 'GK':
                return [
                    'saves' => $gameStats->sum('saves'),
                    'clean_sheets' => $gameStats->where('goals_conceded', 0)->count(),
                    'goals_conceded' => $gameStats->sum('goals_conceded') ?? 0,
                    'save_percentage' => $this->calculateSavePercentage($gameStats),
                    'distribution_accuracy' => $this->calculateDistributionAccuracy($gameStats),
                ];

            case 'CB':
                return [
                    'aerial_duels_won' => $gameStats->sum('aerial_duels_won') ?? 0,
                    'clearances' => $gameStats->sum('clearances') ?? 0,
                    'blocks' => $gameStats->sum('blocks') ?? 0,
                    'tackles_won' => $gameStats->sum('tackles'),
                    'interceptions' => $gameStats->sum('interceptions'),
                    'passing_accuracy' => $this->calculatePassingAccuracy($gameStats),
                    'ball_recoveries' => $gameStats->sum('ball_recoveries') ?? 0,
                ];

            case 'LB':
            case 'RB':
                return [
                    'tackles_won' => $gameStats->sum('tackles'),
                    'interceptions' => $gameStats->sum('interceptions'),
                    'blocks' => $gameStats->sum('blocks') ?? 0,
                    'clearances' => $gameStats->sum('clearances') ?? 0,
                    'crosses_attempted' => $gameStats->sum('crosses_attempted') ?? 0,
                    'cross_accuracy' => $this->calculateCrossAccuracy($gameStats),
                    'key_passes' => $gameStats->sum('key_passes') ?? 0,
                    'dribbles_completed' => $gameStats->sum('dribbles_completed') ?? 0,
                    'progressive_runs' => $gameStats->sum('progressive_runs') ?? 0,
                    'defensive_duels_won' => $gameStats->sum('defensive_duels_won') ?? 0,
                ];

            case 'CDM':
                return [
                    'tackles_won' => $gameStats->sum('tackles'),
                    'interceptions' => $gameStats->sum('interceptions'),
                    'ball_recoveries' => $gameStats->sum('ball_recoveries') ?? 0,
                    'passing_accuracy' => $this->calculatePassingAccuracy($gameStats),
                    'progressive_passes' => $gameStats->sum('progressive_passes') ?? 0,
                    'duels_won' => $gameStats->sum('duels_won') ?? 0,
                ];

            case 'CM':
                return [
                    'passing_accuracy' => $this->calculatePassingAccuracy($gameStats),
                    'key_passes' => $gameStats->sum('key_passes') ?? 0,
                    'expected_assists' => $gameStats->sum('expected_assists') ?? 0,
                    'shots' => $gameStats->sum('shots') ?? 0,
                    'tackles_won' => $gameStats->sum('tackles'),
                    'interceptions' => $gameStats->sum('interceptions'),
                    'ball_progressions' => $gameStats->sum('ball_progressions') ?? 0,
                ];

            case 'CAM':
                return [
                    'key_passes' => $gameStats->sum('key_passes') ?? 0,
                    'expected_goals' => $gameStats->sum('expected_goals') ?? 0,
                    'expected_assists' => $gameStats->sum('expected_assists') ?? 0,
                    'chances_created' => $gameStats->sum('chances_created') ?? 0,
                    'dribbles_completed' => $gameStats->sum('dribbles_completed') ?? 0,
                    'shots_on_target' => $gameStats->sum('shots_on_target'),
                    'through_balls' => $gameStats->sum('through_balls') ?? 0,
                    'passes_into_final_third' => $gameStats->sum('passes_into_final_third') ?? 0,
                ];

            case 'LW':
            case 'RW':
                return [
                    'key_passes' => $gameStats->sum('key_passes') ?? 0,
                    'dribbles_completed' => $gameStats->sum('dribbles_completed') ?? 0,
                    'cross_accuracy' => $this->calculateCrossAccuracy($gameStats),
                    'progressive_runs' => $gameStats->sum('progressive_runs') ?? 0,
                    'shots_on_target' => $gameStats->sum('shots_on_target'),
                    'expected_goals' => $gameStats->sum('expected_goals') ?? 0,
                    'expected_assists' => $gameStats->sum('expected_assists') ?? 0,
                    'chance_creation' => $gameStats->sum('chance_creation') ?? 0,
                ];

            case 'ST':
            case 'CF':
                return [
                    'shots_on_target' => $gameStats->sum('shots_on_target'),
                    'shot_conversion_rate' => $this->calculateConversionRate($gameStats),
                    'expected_goals' => $gameStats->sum('expected_goals') ?? 0,
                    'key_passes' => $gameStats->sum('key_passes') ?? 0,
                    'aerial_duels_won' => $gameStats->sum('aerial_duels_won') ?? 0,
                    'touches_in_box' => $gameStats->sum('touches_in_box') ?? 0,
                    'big_chances_scored' => $gameStats->sum('big_chances_scored') ?? 0,
                    'big_chances_missed' => $gameStats->sum('big_chances_missed') ?? 0,
                    'hold_up_play_success' => $gameStats->sum('hold_up_play_success') ?? 0,
                ];

            default:
                return [];
        }
    }

    /**
     * Calculate skills radar attributes (0-100 scale)
     */
    private function calculateSkillsRadar($gameStats, $advancedStats)
    {
        $position = strtoupper($this->position);
        $appearances = max($gameStats->count(), 1); // Avoid division by zero

        // Base calculations from advanced stats
        $radar = [];

        switch ($position) {
            case 'GK':
                $radar = [
                    'shot_stopping' => min(100, ($advancedStats['save_percentage'] ?? 0) * 100),
                    'distribution' => min(100, $advancedStats['distribution_accuracy'] ?? 70),
                    'aerial_ability' => min(100, ($advancedStats['clean_sheets'] ?? 0) / $appearances * 50 + 50),
                    'command_area' => min(100, ($advancedStats['high_claims'] ?? 0) / $appearances * 25 + 60),
                    'handling' => min(100, ($advancedStats['saves'] ?? 0) / $appearances * 10 + 60),
                    'positioning' => 75, // Default for GK
                ];
                break;

            case 'CB':
                $radar = [
                    'tackling' => min(100, ($advancedStats['tackles_won'] ?? 0) / $appearances * 15 + 60),
                    'interceptions' => min(100, ($advancedStats['interceptions'] ?? 0) / $appearances * 20 + 50),
                    'aerial_ability' => min(100, ($advancedStats['aerial_duels_won'] ?? 0) / (($advancedStats['aerial_duels_won'] ?? 0) + 5) * 100),
                    'positioning' => min(100, ($advancedStats['clearances'] ?? 0) / $appearances * 10 + 65),
                    'strength' => min(100, ($advancedStats['ball_recoveries'] ?? 0) / $appearances * 8 + 70),
                    'passing' => min(100, $advancedStats['passing_accuracy'] ?? 70),
                ];
                break;

            case 'LB':
            case 'RB':
                $radar = [
                    'pace' => min(100, ($advancedStats['progressive_runs'] ?? 0) / $appearances * 12 + 65),
                    'crossing' => min(100, $advancedStats['cross_accuracy'] ?? 65),
                    'dribbling' => min(100, ($advancedStats['dribbles_completed'] ?? 0) / $appearances * 8 + 60),
                    'tackling' => min(100, ($advancedStats['tackles_won'] ?? 0) / $appearances * 15 + 55),
                    'positioning' => min(100, ($advancedStats['interceptions'] ?? 0) / $appearances * 20 + 60),
                    'stamina' => min(100, ($advancedStats['defensive_duels_won'] ?? 0) / $appearances * 10 + 70),
                ];
                break;

            case 'CDM':
                $radar = [
                    'tackling' => min(100, ($advancedStats['tackles_won'] ?? 0) / $appearances * 15 + 65),
                    'interceptions' => min(100, ($advancedStats['interceptions'] ?? 0) / $appearances * 20 + 60),
                    'positioning' => min(100, ($advancedStats['ball_recoveries'] ?? 0) / $appearances * 12 + 70),
                    'passing' => min(100, $advancedStats['passing_accuracy'] ?? 75),
                    'vision' => min(100, ($advancedStats['progressive_passes'] ?? 0) / $appearances * 10 + 65),
                    'stamina' => min(100, ($advancedStats['duels_won'] ?? 0) / $appearances * 8 + 75),
                ];
                break;

            case 'CM':
                $radar = [
                    'passing' => min(100, $advancedStats['passing_accuracy'] ?? 80),
                    'vision' => min(100, ($advancedStats['key_passes'] ?? 0) / $appearances * 15 + 70),
                    'tackling' => min(100, ($advancedStats['tackles_won'] ?? 0) / $appearances * 12 + 50),
                    'positioning' => min(100, ($advancedStats['interceptions'] ?? 0) / $appearances * 18 + 55),
                    'stamina' => min(100, ($advancedStats['ball_progressions'] ?? 0) / $appearances * 10 + 70),
                    'decisions' => min(100, ($advancedStats['expected_assists'] ?? 0) / $appearances * 20 + 65),
                ];
                break;

            case 'CAM':
                $radar = [
                    'passing' => min(100, ($advancedStats['key_passes'] ?? 0) / $appearances * 12 + 75),
                    'vision' => min(100, ($advancedStats['chances_created'] ?? 0) / $appearances * 15 + 80),
                    'dribbling' => min(100, ($advancedStats['dribbles_completed'] ?? 0) / $appearances * 10 + 70),
                    'finishing' => min(100, ($advancedStats['shots_on_target'] ?? 0) / max($advancedStats['shots_on_target'] ?? 0 + $advancedStats['shots'] ?? 5, 1) * 100),
                    'technique' => min(100, ($advancedStats['through_balls'] ?? 0) / $appearances * 20 + 65),
                    'flair' => min(100, ($advancedStats['expected_goals'] ?? 0) / $appearances * 25 + 60),
                ];
                break;

            case 'LW':
            case 'RW':
                $radar = [
                    'pace' => min(100, ($advancedStats['progressive_runs'] ?? 0) / $appearances * 15 + 80),
                    'crossing' => min(100, $advancedStats['cross_accuracy'] ?? 70),
                    'dribbling' => min(100, ($advancedStats['dribbles_completed'] ?? 0) / $appearances * 12 + 75),
                    'finishing' => min(100, ($advancedStats['shots_on_target'] ?? 0) / max($advancedStats['shots_on_target'] ?? 0 + 2, 1) * 100),
                    'technique' => min(100, ($advancedStats['key_passes'] ?? 0) / $appearances * 10 + 70),
                    'balance' => min(100, ($advancedStats['chance_creation'] ?? 0) / $appearances * 12 + 65),
                ];
                break;

            case 'ST':
            case 'CF':
                $radar = [
                    'finishing' => min(100, $advancedStats['shot_conversion_rate'] ?? 0 * 100),
                    'positioning' => min(100, ($advancedStats['touches_in_box'] ?? 0) / $appearances * 8 + 75),
                    'aerial_ability' => min(100, ($advancedStats['aerial_duels_won'] ?? 0) / (($advancedStats['aerial_duels_won'] ?? 0) + 3) * 100),
                    'pace' => min(100, ($advancedStats['big_chances_scored'] ?? 0) / max($advancedStats['big_chances_scored'] ?? 0 + $advancedStats['big_chances_missed'] ?? 2, 1) * 100),
                    'strength' => min(100, $advancedStats['hold_up_play_success'] ?? 70),
                    'technique' => min(100, ($advancedStats['key_passes'] ?? 0) / $appearances * 8 + 65),
                ];
                break;
        }

        return $radar;
    }

    /**
     * Helper methods for calculations
     */
    private function calculateSavePercentage($gameStats)
    {
        $totalSaves = $gameStats->sum('saves');
        $goalsConceded = $gameStats->sum('goals_conceded') ?? 0;
        $totalShots = $totalSaves + $goalsConceded;

        return $totalShots > 0 ? ($totalSaves / $totalShots) : 0;
    }

    private function calculatePassingAccuracy($gameStats)
    {
        $passesCompleted = $gameStats->sum('passes_completed');
        $totalPasses = $gameStats->sum('passes_attempted') ?? $passesCompleted;

        return $totalPasses > 0 ? ($passesCompleted / $totalPasses) : 0;
    }

    private function calculateCrossAccuracy($gameStats)
    {
        $crossesCompleted = $gameStats->sum('crosses_completed') ?? 0;
        $crossesAttempted = $gameStats->sum('crosses_attempted') ?? 1;

        return $crossesAttempted > 0 ? ($crossesCompleted / $crossesAttempted) : 0;
    }

    private function calculateConversionRate($gameStats)
    {
        $goals = $gameStats->sum('goals_scored');
        $shots = $gameStats->sum('shots') ?? $goals;

        return $shots > 0 ? ($goals / $shots) : 0;
    }
}
