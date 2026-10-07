<?php

namespace App\Services;

use App\Models\AiInsight;
use App\Models\AiInsightsDataSource;
use App\Models\AiInsightsJob;
use App\Models\AiInsightsMetric;
use App\Models\Player;
use App\Models\Attendance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AI Insights Service
 *
 * Main service for managing AI insights data pipeline.
 * Handles data retrieval, caching, freshness checks, and metrics.
 *
 * Features:
 * - Smart caching with TTL
 * - Data freshness indicators
 * - Performance metrics tracking
 * - Support for microservices-ready architecture
 */
class AiInsightsService
{
    protected const CACHE_TTL = 3600; // 1 hour default cache TTL
    protected const FRESHNESS_THRESHOLD_DAYS = 7;

    /**
     * Get all insights for a player with caching
     */
    public function getPlayerInsights(int $playerId, bool $useCache = true): array
    {
        $cacheKey = "ai_insights_player_{$playerId}";

        if ($useCache) {
            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($playerId) {
                return $this->fetchPlayerInsights($playerId);
            });
        }

        return $this->fetchPlayerInsights($playerId);
    }

    /**
     * Fetch insights from database
     */
    protected function fetchPlayerInsights(int $playerId): array
    {
        $insights = AiInsight::forPlayer($playerId)
            ->active()
            ->valid()
            ->with('dataSources')
            ->orderBy('insight_type')
            ->orderBy('generated_at', 'desc')
            ->get();

        return $insights->toArray();
    }

    /**
     * Get insights by type for a player
     */
    public function getInsightsByType(int $playerId, string $type): array
    {
        return Cache::remember(
            "ai_insights_{$playerId}_{$type}",
            self::CACHE_TTL,
            function () use ($playerId, $type) {
                return AiInsight::forPlayer($playerId)
                    ->ofType($type)
                    ->active()
                    ->valid()
                    ->first();
            }
        );
    }

    /**
     * Get data freshness for a player
     */
    public function getDataFreshness(int $playerId): array
    {
        $dataSources = AiInsightsDataSource::forPlayer($playerId)
            ->active()
            ->get();

        $hasRecentData = $dataSources->where('data_uploaded_at', '>=', now()->subDays(self::FRESHNESS_THRESHOLD_DAYS))->isNotEmpty();

        $lastInsight = AiInsight::forPlayer($playerId)
            ->active()
            ->valid()
            ->latest('generated_at')
            ->first();

        return [
            'has_recent_data' => $hasRecentData,
            'last_insight_at' => $lastInsight?->generated_at?->toIso8601String(),
            'days_since_update' => $lastInsight ? $lastInsight->generated_at->diffInDays(now()) : null,
            'data_sources_count' => $dataSources->count(),
            'needs_refresh' => !$hasRecentData || ($lastInsight && $lastInsight->needsRefresh()),
            'freshness_score' => $this->calculateFreshnessScore($playerId),
        ];
    }

    /**
     * Calculate freshness score (0-100)
     */
    protected function calculateFreshnessScore(int $playerId): float
    {
        $dataSources = AiInsightsDataSource::forPlayer($playerId)->active()->get();

        // Check for recent data uploads
        $recentSources = $dataSources->where('data_uploaded_at', '>=', now()->subDays(7));
        if ($recentSources->isNotEmpty()) {
            return 100;
        }

        // Check last insight generation
        $lastInsight = AiInsight::forPlayer($playerId)
            ->active()
            ->latest('generated_at')
            ->first();

        if (!$lastInsight) {
            return 0;
        }

        $hoursSinceGeneration = $lastInsight->generated_at->diffInHours(now());
        return max(0, 100 - ($hoursSinceGeneration / 24) * 10);
    }

    /**
     * Clear player insights cache
     */
    public function clearCache(int $playerId): void
    {
        Cache::forget("ai_insights_player_{$playerId}");

        // Also clear individual type caches
        foreach (AiInsight::getInsightTypes() as $type => $label) {
            Cache::forget("ai_insights_{$playerId}_{$type}");
        }
    }

    /**
     * Record metric for the AI system
     */
    public function recordMetric(
        string $type,
        string $name,
        float $value,
        ?string $unit = null,
        ?int $playerId = null,
        ?string $insightType = null,
        ?array $context = null
    ): AiInsightsMetric {
        return AiInsightsMetric::record($type, $name, $value, $unit, $playerId, $insightType, $context);
    }

    /**
     * Get performance metrics for the AI system
     */
    public function getPerformanceMetrics(int $days = 30): array
    {
        return [
            'processing_time' => AiInsightsMetric::getAggregated(
                AiInsightsMetric::TYPE_PROCESSING_TIME,
                'daily',
                null,
                $days
            ),
            'data_freshness' => AiInsightsMetric::getAggregated(
                AiInsightsMetric::TYPE_DATA_FRESHNESS,
                'daily',
                null,
                $days
            ),
            'insights_generated' => AiInsightsMetric::getAggregated(
                AiInsightsMetric::TYPE_INSIGHTS_GENERATED,
                'daily',
                null,
                $days
            ),
            'cache_hit_rate' => AiInsightsMetric::getAggregated(
                AiInsightsMetric::TYPE_CACHE_HIT_RATE,
                'daily',
                null,
                $days
            ),
        ];
    }

    /**
     * Get user engagement metrics
     */
    public function getEngagementMetrics(int $playerId, int $days = 30): array
    {
        return AiInsightsAnalytic::getEngagementMetrics($playerId, $days);
    }

    /**
     * Check if insights need refresh
     */
    public function insightsNeedRefresh(int $playerId): bool
    {
        $dataSources = AiInsightsDataSource::forPlayer($playerId)->active()->get();

        // Check if any data source has new data
        $hasNewData = $dataSources->where('data_uploaded_at', '>=', now()->subDays(7))->isNotEmpty();

        if ($hasNewData) {
            return true;
        }

        // Check if any insights are stale
        $staleInsights = AiInsight::forPlayer($playerId)
            ->active()
            ->where('is_manually_overridden', false)
            ->get()
            ->filter(fn($insight) => $insight->needsRefresh());

        return $staleInsights->isNotEmpty();
    }

    /**
     * Register a data source for a player
     */
    public function registerDataSource(
        int $playerId,
        string $type,
        string $name,
        ?string $identifier = null,
        ?array $config = null,
        bool $autoSync = true,
        string $syncFrequency = AiInsightsDataSource::FREQUENCY_DAILY
    ): AiInsightsDataSource {
        return AiInsightsDataSource::create([
            'player_id' => $playerId,
            'source_type' => $type,
            'source_name' => $name,
            'source_identifier' => $identifier,
            'source_config' => $config,
            'is_active' => true,
            'auto_sync_enabled' => $autoSync,
            'sync_frequency' => $syncFrequency,
        ]);
    }

    /**
     * Record data upload for a source
     */
    public function recordDataUpload(int $sourceId, int $dataPointsCount = 0): void
    {
        $source = AiInsightsDataSource::findOrFail($sourceId);
        $source->recordDataUpload($dataPointsCount);

        // Clear cache to trigger regeneration
        $this->clearCache($source->player_id);
    }

    /**
     * Get attendance metrics for a player
     */
    public function getAttendanceMetrics(int $playerId, int $days = 90): array
    {
        $cacheKey = "attendance_metrics_{$playerId}_{$days}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($playerId, $days) {
            return $this->calculateAttendanceMetrics($playerId, $days);
        });
    }

    /**
     * Calculate attendance metrics for a player
     */
    protected function calculateAttendanceMetrics(int $playerId, int $days): array
    {
        $startDate = now()->subDays($days);

        $attendances = Attendance::where('player_id', $playerId)
            ->where('session_date', '>=', $startDate)
            ->get();

        if ($attendances->isEmpty()) {
            return [
                'total_sessions' => 0,
                'attended_sessions' => 0,
                'missed_sessions' => 0,
                'attendance_rate' => 0,
                'total_scheduled_minutes' => 0,
                'total_actual_minutes' => 0,
                'total_missed_minutes' => 0,
                'training_exposure_ratio' => 0,
                'lateness_frequency' => 0,
                'meeting_attendance_rate' => 0,
                'discipline_score' => 0,
                'attendance_trend' => 'insufficient_data',
                'data_points' => 0,
            ];
        }

        $totalSessions = $attendances->count();
        $attendedSessions = $attendances->whereNotNull('check_in_time')->count();
        $missedSessions = $totalSessions - $attendedSessions;

        $attendanceRate = $totalSessions > 0 ? ($attendedSessions / $totalSessions) * 100 : 0;

        // Calculate time-based metrics
        $totalScheduledMinutes = $attendances->sum('total_duration_minutes') ?? 0;
        $totalActualMinutes = $attendances->whereNotNull('check_out_time')->sum('total_duration_minutes') ?? 0;
        $totalMissedMinutes = $attendances->whereNull('check_in_time')->sum('total_duration_minutes') ?? 0;

        $trainingExposureRatio = $totalScheduledMinutes > 0 ? ($totalActualMinutes / $totalScheduledMinutes) : 0;

        // Calculate lateness frequency
        $lateSessions = $attendances->where('lateness_category', 'late')
            ->orWhere('lateness_category', 'very_late')
            ->count();
        $latenessFrequency = $attendedSessions > 0 ? ($lateSessions / $attendedSessions) * 100 : 0;

        // Meeting attendance rate
        $meetingSessions = $attendances->where('session_type', 'meeting')->count();
        $attendedMeetings = $attendances->where('session_type', 'meeting')
            ->whereNotNull('check_in_time')
            ->count();
        $meetingAttendanceRate = $meetingSessions > 0 ? ($attendedMeetings / $meetingSessions) * 100 : 0;

        // Discipline score (inverse of lateness and absence)
        $disciplineScore = max(0, 100 - ($latenessFrequency * 0.5) - (($missedSessions / $totalSessions) * 100 * 0.5));

        // Attendance trend analysis
        $attendanceTrend = $this->calculateAttendanceTrend($attendances);

        return [
            'total_sessions' => $totalSessions,
            'attended_sessions' => $attendedSessions,
            'missed_sessions' => $missedSessions,
            'attendance_rate' => round($attendanceRate, 2),
            'total_scheduled_minutes' => $totalScheduledMinutes,
            'total_actual_minutes' => $totalActualMinutes,
            'total_missed_minutes' => $totalMissedMinutes,
            'training_exposure_ratio' => round($trainingExposureRatio, 3),
            'lateness_frequency' => round($latenessFrequency, 2),
            'meeting_attendance_rate' => round($meetingAttendanceRate, 2),
            'discipline_score' => round($disciplineScore, 2),
            'attendance_trend' => $attendanceTrend,
            'data_points' => $totalSessions,
        ];
    }

    /**
     * Calculate attendance trend
     */
    protected function calculateAttendanceTrend($attendances): string
    {
        if ($attendances->count() < 4) {
            return 'insufficient_data';
        }

        // Split into two halves
        $sortedAttendances = $attendances->sortBy('session_date');
        $halfCount = (int) floor($sortedAttendances->count() / 2);
        $firstHalf = $sortedAttendances->take($halfCount);
        $secondHalf = $sortedAttendances->slice($halfCount);

        $firstHalfAttendanceRate = $this->calculatePeriodAttendanceRate($firstHalf);
        $secondHalfAttendanceRate = $this->calculatePeriodAttendanceRate($secondHalf);

        $change = $secondHalfAttendanceRate - $firstHalfAttendanceRate;

        if ($change > 10) return 'improving';
        if ($change < -10) return 'declining';
        return 'stable';
    }

    /**
     * Calculate attendance rate for a period
     */
    protected function calculatePeriodAttendanceRate($attendances): float
    {
        if ($attendances->isEmpty()) return 0;

        $attended = $attendances->whereNotNull('check_in_time')->count();
        return ($attended / $attendances->count()) * 100;
    }

    /**
     * Get system status overview
     */
    public function getSystemStatus(): array
    {
        $totalPlayers = Player::count();
        $playersWithInsights = AiInsight::distinct('player_id')->count('player_id');
        $activeJobs = AiInsightsJob::running()->count();
        $pendingJobs = AiInsightsJob::pending()->count();
        $failedJobs = AiInsightsJob::failed()->count();

        return [
            'total_players' => $totalPlayers,
            'players_with_insights' => $playersWithInsights,
            'coverage_percentage' => $totalPlayers > 0
                ? round(($playersWithInsights / $totalPlayers) * 100, 2)
                : 0,
            'active_jobs' => $activeJobs,
            'pending_jobs' => $pendingJobs,
            'failed_jobs' => $failedJobs,
            'system_health' => $failedJobs === 0 ? 'healthy' : 'degraded',
        ];
    }
}
