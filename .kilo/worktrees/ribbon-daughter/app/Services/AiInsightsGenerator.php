<?php

namespace App\Services;

use App\Models\AiInsight;
use App\Models\AiInsightsDataSource;
use App\Models\AiInsightsJob;
use App\Models\AiInsightsMetric;
use App\Models\Player;
use App\Models\PlayerGameStats;
use App\Models\Attendance;
use App\Services\AiInsightsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AI Insights Generator Service
 *
 * Generates AI-powered insights for players based on available data.
 * This is the core engine for creating dynamic, data-driven insights.
 *
 * Features:
 * - Multi-source data analysis
 * - Performance trend detection
 * - Predictive modeling foundation
 * - Confidence scoring
 */
class AiInsightsGenerator
{
    protected string $version = '1.0.0';
    protected string $modelName = 'vipers-analyst-v1';

    /**
     * Generate all insights for a player
     */
    public function generatePlayerInsights(int $playerId, bool $forceRefresh = false): array
    {
        $startTime = microtime(true);
        $player = Player::findOrFail($playerId);

        $insights = [];
        $dataPointsUsed = 0;

        try {
            // Get available data sources
            $dataSources = AiInsightsDataSource::forPlayer($playerId)->active()->get();

            // Gather data from various sources
            $gameStats = PlayerGameStats::where('player_id', $playerId)->get();
            $attendanceMetrics = app(AiInsightsService::class)->getAttendanceMetrics($playerId, 90);
            $dataPointsUsed = $gameStats->count() + $attendanceMetrics['data_points'];

            // Generate each type of insight with attendance data
            $insights['strength'] = $this->generateStrengthInsight($player, $gameStats, $dataSources, $attendanceMetrics);
            $insights['development'] = $this->generateDevelopmentInsight($player, $gameStats, $dataSources, $attendanceMetrics);
            $insights['trend'] = $this->generateTrendInsight($player, $gameStats, $attendanceMetrics);
            $insights['style'] = $this->generateStyleInsight($player, $gameStats, $attendanceMetrics);
            $insights['prediction'] = $this->generatePredictionInsight($player, $gameStats, $attendanceMetrics);

            // Save insights
            $generatedInsights = [];
            foreach ($insights as $type => $insightData) {
                $generatedInsights[] = $this->saveInsight($playerId, $type, $insightData, $dataPointsUsed);
            }

            // Record generation time
            $generationTime = (microtime(true) - $startTime) * 1000;
            $this->recordGenerationMetrics($playerId, count($generatedInsights), $generationTime);

            return $generatedInsights;

        } catch (\Exception $e) {
            Log::error("Failed to generate insights for player {$playerId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate strength insight
     */
    protected function generateStrengthInsight(Player $player, $gameStats, $dataSources, array $attendanceMetrics = []): array
    {
        $strengths = [];
        $confidence = 0;
        $dataPoints = $gameStats->count();

        if ($dataPoints === 0) {
            return [
                'content' => "Based on available data, {$player->name} shows potential for development. More game data will help identify specific strengths.",
                'confidence_level' => 'low',
                'confidence_score' => 30,
                'insight_data' => ['strengths' => []],
            ];
        }

        // Analyze goals
        $avgGoals = $gameStats->avg('goals_scored') ?? 0;
        if ($avgGoals > 0.5) {
            $strengths[] = "Consistent goal-scoring ability with an average of " . round($avgGoals, 2) . " goals per match";
            $confidence += 20;
        }

        // Analyze assists
        $avgAssists = $gameStats->avg('assists') ?? 0;
        if ($avgAssists > 0.3) {
            $strengths[] = "Strong creative play with an average of " . round($avgAssists, 2) . " assists per match";
            $confidence += 15;
        }

        // Analyze rating
        $avgRating = $gameStats->avg('rating') ?? 0;
        if ($avgRating >= 7.5) {
            $strengths[] = "Consistently high performance ratings averaging " . round($avgRating, 2) . "/10";
            $confidence += 25;
        }

        // Analyze position-specific strengths
        if (in_array(strtolower($player->position ?? ''), ['forward', 'striker', 'attacker'])) {
            $shotsOnTarget = $gameStats->avg('shots_on_target') ?? 0;
            if ($shotsOnTarget > 2) {
                $strengths[] = "Good shot accuracy with an average of " . round($shotsOnTarget, 1) . " shots on target per match";
                $confidence += 15;
            }
        } elseif (in_array(strtolower($player->position ?? ''), ['midfielder', 'central midfielder'])) {
            $passes = $gameStats->avg('passes_completed') ?? 0;
            if ($passes > 30) {
                $strengths[] = "Strong passing ability with an average of " . round($passes, 1) . " completed passes per match";
                $confidence += 15;
            }
        } elseif (in_array(strtolower($player->position ?? ''), ['defender', 'center back'])) {
            $tackles = $gameStats->avg('tackles') ?? 0;
            if ($tackles > 3) {
                $strengths[] = "Solid defensive capabilities with an average of " . round($tackles, 1) . " tackles per match";
                $confidence += 15;
            }
        }

        // Incorporate attendance data into strength analysis
        $attendanceInsights = [];
        if (!empty($attendanceMetrics) && $attendanceMetrics['data_points'] > 0) {
            $disciplineScore = $attendanceMetrics['discipline_score'];
            $trainingExposureRatio = $attendanceMetrics['training_exposure_ratio'];

            if ($disciplineScore >= 80) {
                $attendanceInsights[] = "Excellent attendance discipline with a {$disciplineScore}% discipline score";
                $confidence += 10;
            } elseif ($disciplineScore < 60) {
                $attendanceInsights[] = "Attendance discipline needs improvement ({$disciplineScore}% discipline score)";
            }

            if ($trainingExposureRatio >= 0.8) {
                $attendanceInsights[] = "High training exposure ratio of " . round($trainingExposureRatio * 100, 1) . "% indicates commitment to development";
                $confidence += 5;
            }
        }

        // Combine game performance and attendance insights
        $allStrengths = array_merge($strengths, $attendanceInsights);

        // Determine confidence level
        $confidenceLevel = $this->getConfidenceLevel($confidence, $dataPoints);
        $confidenceScore = min(100, $confidence + ($dataPoints * 5));

        return [
            'content' => !empty($allStrengths)
                ? implode(". ", $allStrengths) . "."
                : "{$player->name} shows steady performance across multiple areas. Continued game time and training attendance will help identify specific strengths.",
            'confidence_level' => $confidenceLevel,
            'confidence_score' => $confidenceScore,
            'insight_data' => [
                'strengths' => $allStrengths,
                'game_metrics' => [
                    'avg_goals' => round($avgGoals, 2),
                    'avg_assists' => round($avgAssists, 2),
                    'avg_rating' => round($avgRating, 2),
                    'data_points' => $dataPoints,
                ],
                'attendance_metrics' => $attendanceMetrics,
            ],
        ];
    }

    /**
     * Generate development area insight
     */
    protected function generateDevelopmentInsight(Player $player, $gameStats, $dataSources, array $attendanceMetrics = []): array
    {
        $developments = [];
        $confidence = 0;
        $dataPoints = $gameStats->count();

        if ($dataPoints === 0) {
            return [
                'content' => "Insufficient data to identify specific development areas. Recommend collecting more game statistics.",
                'confidence_level' => 'low',
                'confidence_score' => 20,
                'insight_data' => ['areas' => []],
            ];
        }

        // Analyze yellow cards (discipline)
        $avgYellowCards = $gameStats->avg('yellow_cards') ?? 0;
        if ($avgYellowCards > 0.3) {
            $developments[] = "Focus on disciplinary consistency - averaging " . round($avgYellowCards, 2) . " yellow cards per match";
            $confidence += 10;
        }

        // Analyze minutes played consistency
        $avgMinutes = $gameStats->avg('minutes_played') ?? 0;
        if ($avgMinutes < 60 && $dataPoints > 3) {
            $developments[] = "Work on building consistency to earn more playing time";
            $confidence += 15;
        }

        // Analyze goal conversion
        $totalShots = $gameStats->sum('shots_on_target') ?? 0;
        $totalGoals = $gameStats->sum('goals_scored') ?? 0;
        if ($totalShots > 10 && $totalGoals > 0) {
            $conversionRate = ($totalGoals / $totalShots) * 100;
            if ($conversionRate < 20) {
                $developments[] = "Improve goal conversion rate - currently at " . round($conversionRate, 1) . "%";
                $confidence += 20;
            }
        }

        // Position-specific development areas
        $position = strtolower($player->position ?? '');
        if (in_array($position, ['forward', 'striker', 'attacker'])) {
            $interceptions = $gameStats->avg('interceptions') ?? 0;
            if ($interceptions < 0.5) {
                $developments[] = "Improve defensive awareness and positioning off the ball";
                $confidence += 10;
            }
        } elseif ($position === 'goalkeeper') {
            $saves = $gameStats->avg('saves') ?? 0;
            if ($saves < 3 && $dataPoints > 3) {
                $developments[] = "Work on shot-stopping consistency";
                $confidence += 10;
            }
        }

        // Incorporate attendance data into development analysis
        $attendanceDevelopments = [];
        if (!empty($attendanceMetrics) && $attendanceMetrics['data_points'] > 0) {
            $attendanceRate = $attendanceMetrics['attendance_rate'];
            $latenessFrequency = $attendanceMetrics['lateness_frequency'];
            $trainingExposureRatio = $attendanceMetrics['training_exposure_ratio'];

            if ($attendanceRate < 70) {
                $attendanceDevelopments[] = "Improve session attendance - currently at {$attendanceRate}% attendance rate";
                $confidence += 15;
            }

            if ($latenessFrequency > 30) {
                $attendanceDevelopments[] = "Address punctuality issues - {$latenessFrequency}% of attended sessions were late";
                $confidence += 10;
            }

            if ($trainingExposureRatio < 0.6) {
                $attendanceDevelopments[] = "Increase training participation to maximize development opportunities";
                $confidence += 10;
            }
        }

        // Combine game performance and attendance development areas
        $allDevelopments = array_merge($developments, $attendanceDevelopments);

        $confidenceLevel = $this->getConfidenceLevel($confidence, $dataPoints);
        $confidenceScore = min(100, $confidence + ($dataPoints * 3));

        return [
            'content' => !empty($allDevelopments)
                ? implode(". ", $allDevelopments) . "."
                : "No specific development areas identified. Continue building on current performance levels and maintain consistent training attendance.",
            'confidence_level' => $confidenceLevel,
            'confidence_score' => $confidenceScore,
            'insight_data' => [
                'areas' => $allDevelopments,
                'game_metrics' => [
                    'avg_yellow_cards' => round($avgYellowCards, 2),
                    'avg_minutes' => round($avgMinutes, 1),
                    'conversion_rate' => isset($conversionRate) ? round($conversionRate, 1) : null,
                    'data_points' => $dataPoints,
                ],
                'attendance_metrics' => $attendanceMetrics,
            ],
        ];
    }

    /**
     * Generate trend insight
     */
    protected function generateTrendInsight(Player $player, $gameStats, array $attendanceMetrics = []): array
    {
        $dataPoints = $gameStats->count();

        if ($dataPoints < 3) {
            return [
                'content' => "Insufficient data to identify performance trends. At least 3 matches of data required for trend analysis.",
                'confidence_level' => 'low',
                'confidence_score' => 20,
                'insight_data' => ['trend' => 'insufficient_data'],
            ];
        }

        // Sort by game date
        $sortedStats = $gameStats->sortBy('game_date');

        // Split into two halves for comparison
        $halfCount = (int) floor($dataPoints / 2);
        $firstHalf = $sortedStats->take($halfCount);
        $secondHalf = $sortedStats->slice($halfCount);

        $firstAvgRating = $firstHalf->avg('rating') ?? 0;
        $secondAvgRating = $secondHalf->avg('rating') ?? 0;

        $ratingChange = $secondAvgRating - $firstAvgRating;
        $percentChange = $firstAvgRating > 0 ? ($ratingChange / $firstAvgRating) * 100 : 0;

        $trend = 'stable';
        $description = "Consistent performance level maintained over recent matches.";

        if ($percentChange > 10) {
            $trend = 'improving';
            $description = "Showing positive momentum with a " . round(abs($percentChange), 1) . "% improvement in average ratings over recent matches.";
        } elseif ($percentChange < -10) {
            $trend = 'declining';
            $description = "Recent performance shows a " . round(abs($percentChange), 1) . "% decline in average ratings. Consider reviewing training and match preparation.";
        }

        return [
            'content' => $description,
            'confidence_level' => $dataPoints >= 10 ? 'high' : 'medium',
            'confidence_score' => min(100, 50 + ($dataPoints * 5)),
            'insight_data' => [
                'trend' => $trend,
                'rating_change' => round($ratingChange, 2),
                'percent_change' => round($percentChange, 1),
                'first_half_avg' => round($firstAvgRating, 2),
                'second_half_avg' => round($secondAvgRating, 2),
                'data_points' => $dataPoints,
            ],
        ];
    }

    /**
     * Generate playing style insight
     */
    protected function generateStyleInsight(Player $player, $gameStats, array $attendanceMetrics = []): array
    {
        $position = strtolower($player->position ?? '');
        $dataPoints = $gameStats->count();

        $styleAnalysis = [];

        // Determine play style based on statistics
        $avgGoals = $gameStats->avg('goals_scored') ?? 0;
        $avgAssists = $gameStats->avg('assists') ?? 0;
        $avgTackles = $gameStats->avg('tackles') ?? 0;
        $avgInterceptions = $gameStats->avg('interceptions') ?? 0;

        // Calculate contribution ratio
        $totalContributions = $avgGoals + $avgAssists;
        $totalDefensiveActions = $avgTackles + $avgInterceptions;

        if ($totalContributions > $totalDefensiveActions * 2) {
            $styleAnalysis['primary_role'] = 'Attacking Player';
            $styleAnalysis['description'] = "Primarily contributes to attacking play with a focus on goals and assists.";
        } elseif ($totalDefensiveActions > $totalContributions * 2) {
            $styleAnalysis['primary_role'] = 'Defensive Player';
            $styleAnalysis['description'] = "Primarily contributes to defensive stability with strong tackling and interception numbers.";
        } else {
            $styleAnalysis['primary_role'] = 'Balanced Player';
            $styleAnalysis['description'] = "Contributes effectively to both attacking and defensive phases of play.";
        }

        // Add position context
        if ($position) {
            $positionMap = [
                'forward' => 'striker role',
                'striker' => 'goal scorer role',
                'attacker' => 'attacking role',
                'midfielder' => 'midfield engine role',
                'defender' => 'defensive role',
                'center back' => 'central defense role',
                'goalkeeper' => 'last line of defense',
            ];

            if (isset($positionMap[$position])) {
                $styleAnalysis['position_role'] = $positionMap[$position];
            }
        }

        $styleAnalysis['player_type'] = match (true) {
            $avgGoals > 1 => 'Goal Machine',
            $avgGoals > 0.5 => 'Consistent Scorer',
            $avgAssists > 0.5 => 'Playmaker',
            $avgTackles > 4 => 'Defensive Anchor',
            default => 'Team Player',
        };

        // Incorporate attendance data into style analysis
        $commitmentLevel = "unknown";
        if (!empty($attendanceMetrics) && $attendanceMetrics['data_points'] > 0) {
            $disciplineScore = $attendanceMetrics['discipline_score'];
            $trainingExposureRatio = $attendanceMetrics['training_exposure_ratio'];

            if ($disciplineScore >= 85 && $trainingExposureRatio >= 0.8) {
                $commitmentLevel = "highly committed";
                $styleAnalysis['commitment_description'] = "Demonstrates exceptional commitment with high attendance rates and punctuality.";
            } elseif ($disciplineScore >= 70 && $trainingExposureRatio >= 0.6) {
                $commitmentLevel = "committed";
                $styleAnalysis['commitment_description'] = "Shows good commitment to training and development opportunities.";
            } elseif ($disciplineScore < 60 || $trainingExposureRatio < 0.5) {
                $commitmentLevel = "needs improvement";
                $styleAnalysis['commitment_description'] = "Commitment to training sessions needs improvement to maximize potential.";
            } else {
                $commitmentLevel = "developing";
                $styleAnalysis['commitment_description'] = "Building commitment to the training program.";
            }
        }

        return [
            'content' => "Dynamic {$player->name} operating in a {$styleAnalysis['primary_role']} capacity. " . $styleAnalysis['description'] .
                        (!empty($attendanceMetrics) && $attendanceMetrics['data_points'] > 0 ?
                         " Player shows {$commitmentLevel} commitment to training with " . round($attendanceMetrics['attendance_rate'], 1) . "% attendance rate." :
                         ""),
            'confidence_level' => $dataPoints >= 5 ? 'medium' : 'low',
            'confidence_score' => min(100, 40 + ($dataPoints * 10)),
            'insight_data' => $styleAnalysis + [
                'avg_goals' => round($avgGoals, 2),
                'avg_assists' => round($avgAssists, 2),
                'avg_defensive_actions' => round($totalDefensiveActions, 2),
                'game_data_points' => $dataPoints,
                'commitment_level' => $commitmentLevel,
                'attendance_metrics' => $attendanceMetrics,
            ],
        ];
    }

    /**
     * Generate prediction insight
     */
    protected function generatePredictionInsight(Player $player, $gameStats, array $attendanceMetrics = []): array
    {
        $dataPoints = $gameStats->count();

        if ($dataPoints < 5) {
            return [
                'content' => "Insufficient historical data for reliable predictions. Continue accumulating match data.",
                'confidence_level' => 'low',
                'confidence_score' => 15,
                'insight_data' => ['predictions' => []],
            ];
        }

        // Calculate predicted performance range
        $avgRating = $gameStats->avg('rating') ?? 0;
        $ratingStdDev = $this->calculateStdDev($gameStats->pluck('rating')->filter()->toArray());

        $predictedRatingLow = max(0, $avgRating - $ratingStdDev);
        $predictedRatingHigh = min(10, $avgRating + $ratingStdDev);

        // Predict goals based on trend
        $sortedStats = $gameStats->sortBy('game_date');
        $recentStats = $sortedStats->take(5);
        $recentAvgGoals = $recentStats->avg('goals_scored') ?? 0;

        // Incorporate attendance data into predictions
        $attendancePrediction = "";
        if (!empty($attendanceMetrics) && $attendanceMetrics['data_points'] > 0) {
            $attendanceTrend = $attendanceMetrics['attendance_trend'];
            $disciplineScore = $attendanceMetrics['discipline_score'];

            if ($attendanceTrend === 'improving' && $disciplineScore >= 75) {
                $attendancePrediction = " Growing commitment to training suggests potential for improved consistency and development.";
            } elseif ($attendanceTrend === 'declining' || $disciplineScore < 60) {
                $attendancePrediction = " Attendance patterns may impact the ability to reach predicted performance levels.";
            } else {
                $attendancePrediction = " Current attendance patterns support the predicted performance trajectory.";
            }
        }

        return [
            'content' => "Based on recent performance trends, {$player->name} is expected to maintain a performance level of " .
                round($predictedRatingLow, 1) . "-" . round($predictedRatingHigh, 1) . " in upcoming matches. " .
                "Predicted to contribute approximately " . round($recentAvgGoals, 1) . " goals per match." . $attendancePrediction,
            'confidence_level' => $dataPoints >= 15 ? 'high' : 'medium',
            'confidence_score' => min(100, 30 + ($dataPoints * 4)),
            'insight_data' => [
                'predicted_rating_range' => [
                    'low' => round($predictedRatingLow, 2),
                    'high' => round($predictedRatingHigh, 2),
                    'average' => round($avgRating, 2),
                ],
                'predicted_goals_per_match' => round($recentAvgGoals, 2),
                'game_data_points' => $dataPoints,
                'attendance_trend' => $attendanceMetrics['attendance_trend'] ?? 'insufficient_data',
                'attendance_metrics' => $attendanceMetrics,
            ],
        ];
    }

    /**
     * Save insight to database
     */
    protected function saveInsight(int $playerId, string $type, array $data, int $dataPointsUsed): AiInsight
    {
        // Deactivate old insights of same type
        AiInsight::forPlayer($playerId)
            ->ofType($type)
            ->active()
            ->update(['is_active' => false]);

        return AiInsight::create([
            'player_id' => $playerId,
            'insight_type' => $type,
            'insight_content' => $data['content'],
            'insight_data' => $data['insight_data'] ?? null,
            'confidence_level' => $data['confidence_level'],
            'confidence_score' => $data['confidence_score'],
            'data_sources' => ['game_stats' => true, 'attendance' => true],
            'data_points_used' => $dataPointsUsed,
            'generated_at' => now(),
            'valid_until' => now()->addDays(7),
            'version' => $this->version,
            'model_name' => $this->modelName,
            'generation_time_ms' => null,
            'is_active' => true,
            'is_manually_overridden' => false,
        ]);
    }

    /**
     * Record generation metrics
     */
    protected function recordGenerationMetrics(int $playerId, int $insightsCount, float $generationTime): void
    {
        AiInsightsMetric::record(
            AiInsightsMetric::TYPE_INSIGHTS_GENERATED,
            'insights_generated',
            $insightsCount,
            'count',
            $playerId
        );

        AiInsightsMetric::record(
            AiInsightsMetric::TYPE_PROCESSING_TIME,
            'generation_time_ms',
            $generationTime,
            'milliseconds',
            $playerId
        );
    }

    /**
     * Get confidence level based on score and data points
     */
    protected function getConfidenceLevel(float $confidence, int $dataPoints): string
    {
        $totalScore = $confidence + ($dataPoints * 5);

        if ($totalScore >= 80) return AiInsight::CONFIDENCE_VERY_HIGH;
        if ($totalScore >= 60) return AiInsight::CONFIDENCE_HIGH;
        if ($totalScore >= 40) return AiInsight::CONFIDENCE_MEDIUM;
        return AiInsight::CONFIDENCE_LOW;
    }

    /**
     * Calculate standard deviation
     */
    protected function calculateStdDev(array $values): float
    {
        if (count($values) < 2) return 0;

        $mean = array_sum($values) / count($values);
        $squaredDiffs = array_map(fn($val) => pow($val - $mean, 2), $values);

        return sqrt(array_sum($squaredDiffs) / (count($values) - 1));
    }
}
