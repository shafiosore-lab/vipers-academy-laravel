<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AI Insights Metric Model
 *
 * Tracks performance metrics for the AI insights system.
 * Used for ROI demonstration and system optimization.
 *
 * @property int $id
 * @property string $metric_type
 * @property string $metric_name
 * @property float $metric_value
 * @property string|null $metric_unit
 * @property int|null $player_id
 * @property string|null $insight_type
 * @property array|null $context
 * @property \Carbon\Carbon $recorded_at
 * @property string $period
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AiInsightsMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_type',
        'metric_name',
        'metric_value',
        'metric_unit',
        'player_id',
        'insight_type',
        'context',
        'recorded_at',
        'period',
    ];

    protected $casts = [
        'metric_value' => 'decimal:4',
        'context' => 'array',
        'recorded_at' => 'datetime',
    ];

    /**
     * Metric type constants
     */
    public const TYPE_PREDICTION_ACCURACY = 'prediction_accuracy';
    public const TYPE_USER_ENGAGEMENT = 'user_engagement';
    public const TYPE_PROCESSING_TIME = 'processing_time';
    public const TYPE_API_CALLS = 'api_calls';
    public const TYPE_CACHE_HIT_RATE = 'cache_hit_rate';
    public const TYPE_INSIGHTS_GENERATED = 'insights_generated';
    public const TYPE_DATA_FRESHNESS = 'data_freshness';
    public const TYPE_CONFIDENCE_SCORE = 'confidence_score';

    /**
     * Period constants
     */
    public const PERIOD_REALTIME = 'realtime';
    public const PERIOD_HOURLY = 'hourly';
    public const PERIOD_DAILY = 'daily';
    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';

    /**
     * Get the player this metric relates to
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Scope by metric type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('metric_type', $type);
    }

    /**
     * Scope by period
     */
    public function scopeOfPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope for recent metrics
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('recorded_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope for player-specific metrics
     */
    public function scopeForPlayer($query, int $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    /**
     * Record a new metric
     */
    public static function record(
        string $type,
        string $name,
        float $value,
        ?string $unit = null,
        ?int $playerId = null,
        ?string $insightType = null,
        ?array $context = null,
        string $period = self::PERIOD_REALTIME
    ): self {
        return static::create([
            'metric_type' => $type,
            'metric_name' => $name,
            'metric_value' => $value,
            'metric_unit' => $unit,
            'player_id' => $playerId,
            'insight_type' => $insightType,
            'context' => $context,
            'recorded_at' => now(),
            'period' => $period,
        ]);
    }

    /**
     * Get aggregated metrics for a time period
     */
    public static function getAggregated(
        string $type,
        string $period = 'daily',
        ?int $playerId = null,
        int $days = 30
    ): array {
        $query = static::ofType($type)
            ->ofPeriod($period)
            ->where('recorded_at', '>=', now()->subDays($days));

        if ($playerId) {
            $query->forPlayer($playerId);
        }

        return [
            'average' => $query->avg('metric_value'),
            'min' => $query->min('metric_value'),
            'max' => $query->max('metric_value'),
            'sum' => $query->sum('metric_value'),
            'count' => $query->count(),
        ];
    }

    /**
     * Get all metric types
     */
    public static function getMetricTypes(): array
    {
        return [
            self::TYPE_PREDICTION_ACCURACY => 'Prediction Accuracy',
            self::TYPE_USER_ENGAGEMENT => 'User Engagement',
            self::TYPE_PROCESSING_TIME => 'Processing Time',
            self::TYPE_API_CALLS => 'API Calls',
            self::TYPE_CACHE_HIT_RATE => 'Cache Hit Rate',
            self::TYPE_INSIGHTS_GENERATED => 'Insights Generated',
            self::TYPE_DATA_FRESHNESS => 'Data Freshness',
            self::TYPE_CONFIDENCE_SCORE => 'Confidence Score',
        ];
    }

    /**
     * Get all periods
     */
    public static function getPeriods(): array
    {
        return [
            self::PERIOD_REALTIME => 'Real-time',
            self::PERIOD_HOURLY => 'Hourly',
            self::PERIOD_DAILY => 'Daily',
            self::PERIOD_WEEKLY => 'Weekly',
            self::PERIOD_MONTHLY => 'Monthly',
        ];
    }
}
