<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * AI Insight Model
 *
 * Represents dynamic AI-generated insights for players.
 * Insights are living data points that evolve with new information.
 *
 * @property int $id
 * @property int $player_id
 * @property string $insight_type
 * @property string $insight_content
 * @property array|null $insight_data
 * @property string $confidence_level
 * @property float|null $confidence_score
 * @property string|null $data_source
 * @property array|null $data_sources
 * @property int $data_points_used
 * @property \Carbon\Carbon $generated_at
 * @property \Carbon\Carbon|null $valid_until
 * @property string $version
 * @property string|null $model_name
 * @property array|null $model_parameters
 * @property int|null $generation_time_ms
 * @property bool $is_active
 * @property bool $is_manually_overridden
 * @property string|null $override_note
 * @property int|null $parent_insight_id
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class AiInsight extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'player_id',
        'insight_type',
        'insight_content',
        'insight_data',
        'confidence_level',
        'confidence_score',
        'data_source',
        'data_sources',
        'data_points_used',
        'generated_at',
        'valid_until',
        'version',
        'model_name',
        'model_parameters',
        'generation_time_ms',
        'is_active',
        'is_manually_overridden',
        'override_note',
        'parent_insight_id',
        'metadata',
    ];

    protected $casts = [
        'insight_data' => 'array',
        'data_sources' => 'array',
        'confidence_score' => 'decimal:2',
        'model_parameters' => 'array',
        'metadata' => 'array',
        'generated_at' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'is_manually_overridden' => 'boolean',
        'data_points_used' => 'integer',
        'generation_time_ms' => 'integer',
    ];

    /**
     * Insight type constants
     */
    public const TYPE_STRENGTH = 'strength';
    public const TYPE_DEVELOPMENT = 'development';
    public const TYPE_TREND = 'trend';
    public const TYPE_STYLE = 'style';
    public const TYPE_PREDICTION = 'prediction';
    public const TYPE_COMPARISON = 'comparison';
    public const TYPE_RECOMMENDATION = 'recommendation';
    public const TYPE_RISK = 'risk';
    public const TYPE_OPPORTUNITY = 'opportunity';

    /**
     * Confidence level constants
     */
    public const CONFIDENCE_LOW = 'low';
    public const CONFIDENCE_MEDIUM = 'medium';
    public const CONFIDENCE_HIGH = 'high';
    public const CONFIDENCE_VERY_HIGH = 'very_high';

    /**
     * Get the player that owns the insight
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get parent insight for historical tracking
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(AiInsight::class, 'parent_insight_id');
    }

    /**
     * Get child insights (revisions)
     */
    public function children(): HasMany
    {
        return $this->hasMany(AiInsight::class, 'parent_insight_id');
    }

    /**
     * Get data sources used for this insight
     */
    public function dataSources(): HasMany
    {
        return $this->hasMany(AiInsightsDataSource::class, 'player_id', 'player_id')
            ->where('is_active', true);
    }

    /**
     * Scope for active insights
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific player
     */
    public function scopeForPlayer($query, int $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    /**
     * Scope by insight type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('insight_type', $type);
    }

    /**
     * Scope for valid insights (not expired)
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('valid_until')
              ->orWhere('valid_until', '>', now());
        });
    }

    /**
     * Scope for recent insights
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('generated_at', '>=', now()->subDays($days));
    }

    /**
     * Scope by confidence level
     */
    public function scopeWithConfidence($query, string $level)
    {
        return $query->where('confidence_level', $level);
    }

    /**
     * Get cached insights for a player
     */
    public static function getCachedForPlayer(int $playerId, int $ttl = 3600): array
    {
        return Cache::remember("ai_insights_player_{$playerId}", $ttl, function () use ($playerId) {
            return static::forPlayer($playerId)
                ->active()
                ->valid()
                ->with('dataSources')
                ->get()
                ->toArray();
        });
    }

    /**
     * Get freshness indicator for this insight
     */
    public function getFreshnessIndicator(): array
    {
        $hoursSinceGeneration = $this->generated_at->diffInHours(now());

        if ($hoursSinceGeneration < 24) {
            return ['status' => 'fresh', 'color' => 'green', 'label' => 'Just updated'];
        } elseif ($hoursSinceGeneration < 72) {
            return ['status' => 'recent', 'color' => 'blue', 'label' => 'Updated recently'];
        } elseif ($hoursSinceGeneration < 168) {
            return ['status' => 'aging', 'color' => 'yellow', 'label' => 'Starting to age'];
        } else {
            return ['status' => 'stale', 'color' => 'red', 'label' => 'Needs update'];
        }
    }

    /**
     * Get data freshness score (0-100)
     */
    public function getDataFreshnessScore(): float
    {
        // Check if any data sources have recent uploads
        $recentSource = $this->dataSources()
            ->where('data_uploaded_at', '>=', now()->subDays(7))
            ->first();

        if ($recentSource) {
            return 100;
        }

        // Calculate based on generation time
        $hoursSinceGeneration = $this->generated_at->diffInHours(now());
        return max(0, 100 - ($hoursSinceGeneration / 24) * 10);
    }

    /**
     * Check if insight needs refresh
     */
    public function needsRefresh(): bool
    {
        if ($this->is_manually_overridden) {
            return false;
        }

        if ($this->valid_until && now()->isAfter($this->valid_until)) {
            return true;
        }

        // Default refresh after 7 days
        return $this->generated_at->diffInDays(now()) >= 7;
    }

    /**
     * Generate a new insight as a revision
     */
    public function createRevision(array $newData): AiInsight
    {
        return static::create(array_merge($newData, [
            'parent_insight_id' => $this->id,
            'is_active' => true,
        ));
    }

    /**
     * Get all insight types
     */
    public static function getInsightTypes(): array
    {
        return [
            self::TYPE_STRENGTH => 'Strengths',
            self::TYPE_DEVELOPMENT => 'Areas for Development',
            self::TYPE_TREND => 'Performance Trend',
            self::TYPE_STYLE => 'Playing Style',
            self::TYPE_PREDICTION => 'Predictions',
            self::TYPE_COMPARISON => 'Comparisons',
            self::TYPE_RECOMMENDATION => 'Recommendations',
            self::TYPE_RISK => 'Risk Factors',
            self::TYPE_OPPORTUNITY => 'Opportunities',
        ];
    }

    /**
     * Get all confidence levels
     */
    public static function getConfidenceLevels(): array
    {
        return [
            self::CONFIDENCE_LOW => 'Low Confidence',
            self::CONFIDENCE_MEDIUM => 'Medium Confidence',
            self::CONFIDENCE_HIGH => 'High Confidence',
            self::CONFIDENCE_VERY_HIGH => 'Very High Confidence',
        ];
    }
}
