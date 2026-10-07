<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AI Insights Data Source Model
 *
 * Tracks data sources used for generating AI insights.
 * Supports multiple source types: game_stats, training, biometric, scouting, external.
 *
 * @property int $id
 * @property int $player_id
 * @property string $source_type
 * @property string $source_name
 * @property string|null $source_identifier
 * @property array|null $source_config
 * @property \Carbon\Carbon|null $last_synced_at
 * @property \Carbon\Carbon|null $data_uploaded_at
 * @property int $data_points_count
 * @property bool $is_active
 * @property bool $auto_sync_enabled
 * @property string $sync_frequency
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AiInsightsDataSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'source_type',
        'source_name',
        'source_identifier',
        'source_config',
        'last_synced_at',
        'data_uploaded_at',
        'data_points_count',
        'is_active',
        'auto_sync_enabled',
        'sync_frequency',
    ];

    protected $casts = [
        'source_config' => 'array',
        'last_synced_at' => 'datetime',
        'data_uploaded_at' => 'datetime',
        'data_points_count' => 'integer',
        'is_active' => 'boolean',
        'auto_sync_enabled' => 'boolean',
    ];

    /**
     * Source type constants
     */
    public const TYPE_GAME_STATS = 'game_stats';
    public const TYPE_TRAINING = 'training';
    public const TYPE_BIOMETRIC = 'biometric';
    public const TYPE_SCOUTING = 'scouting';
    public const TYPE_EXTERNAL = 'external';
    public const TYPE_HISTORICAL = 'historical';

    /**
     * Sync frequency constants
     */
    public const FREQUENCY_HOURLY = 'hourly';
    public const FREQUENCY_DAILY = 'daily';
    public const FREQUENCY_WEEKLY = 'weekly';
    public const FREQUENCY_MANUAL = 'manual';

    /**
     * Get the player that owns this data source
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Scope for active sources
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sources with auto-sync enabled
     */
    public function scopeAutoSync($query)
    {
        return $query->where('auto_sync_enabled', true);
    }

    /**
     * Scope by source type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('source_type', $type);
    }

    /**
     * Scope for sources with recent uploads
     */
    public function scopeWithRecentUploads($query, int $days = 7)
    {
        return $query->where('data_uploaded_at', '>=', now()->subDays($days));
    }

    /**
     * Check if this source has new data
     */
    public function hasNewData(): bool
    {
        return $this->data_uploaded_at !== null
            && $this->data_uploaded_at->gte($this->last_synced_at ?? now()->subDays(30));
    }

    /**
     * Mark as synced
     */
    public function markAsSynced(int $dataPointsAdded = 0): void
    {
        $this->update([
            'last_synced_at' => now(),
            'data_points_count' => $this->data_points_count + $dataPointsAdded,
        ]);
    }

    /**
     * Record data upload
     */
    public function recordDataUpload(int $dataPointsCount = 0): void
    {
        $this->update([
            'data_uploaded_at' => now(),
            'data_points_count' => $this->data_points_count + $dataPointsCount,
        ]);

        // Clear player insights cache to trigger regeneration
        \Illuminate\Support\Facades\Cache::forget("ai_insights_player_{$this->player_id}");
    }

    /**
     * Get all source types
     */
    public static function getSourceTypes(): array
    {
        return [
            self::TYPE_GAME_STATS => 'Game Statistics',
            self::TYPE_TRAINING => 'Training Data',
            self::TYPE_BIOMETRIC => 'Biometric Data',
            self::TYPE_SCOUTING => 'Scouting Reports',
            self::TYPE_EXTERNAL => 'External Data',
            self::TYPE_HISTORICAL => 'Historical Records',
        ];
    }

    /**
     * Get all sync frequencies
     */
    public static function getSyncFrequencies(): array
    {
        return [
            self::FREQUENCY_HOURLY => 'Hourly',
            self::FREQUENCY_DAILY => 'Daily',
            self::FREQUENCY_WEEKLY => 'Weekly',
            self::FREQUENCY_MANUAL => 'Manual',
        ];
    }
}
