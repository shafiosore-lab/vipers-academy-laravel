<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AI Insights Analytic Model
 *
 * Tracks user engagement with AI insights.
 * Provides KPIs for demonstrating ROI to potential investors.
 *
 * @property int $id
 * @property int $player_id
 * @property string $insight_type
 * @property string $user_type
 * @property int|null $user_id
 * @property string|null $session_id
 * @property string $action
 * @property int|null $view_duration_ms
 * @property string|null $device_type
 * @property string|null $ip_hash
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AiInsightsAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'insight_type',
        'user_type',
        'user_id',
        'session_id',
        'action',
        'view_duration_ms',
        'device_type',
        'ip_hash',
    ];

    protected $casts = [
        'player_id' => 'integer',
        'user_id' => 'integer',
        'view_duration_ms' => 'integer',
    ];

    /**
     * User type constants
     */
    public const USER_VISITOR = 'visitor';
    public const USER_REGISTERED = 'registered';
    public const USER_ADMIN = 'admin';
    public const USER_SCOUT = 'scout';

    /**
     * Action constants
     */
    public const ACTION_VIEW = 'view';
    public const ACTION_EXPAND = 'expand';
    public const ACTION_SHARE = 'share';
    public const ACTION_EXPORT = 'export';
    public const ACTION_COMPARE = 'compare';

    /**
     * Device type constants
     */
    public const DEVICE_DESKTOP = 'desktop';
    public const DEVICE_MOBILE = 'mobile';
    public const DEVICE_TABLET = 'tablet';

    /**
     * Get the player this analytic relates to
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Scope by action
     */
    public function scopeWithAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope by user type
     */
    public function scopeForUserType($query, string $userType)
    {
        return $query->where('user_type', $userType);
    }

    /**
     * Scope for a specific session
     */
    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for recent analytics
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Record a new analytic event
     */
    public static function record(
        int $playerId,
        string $insightType,
        string $action,
        string $userType = self::USER_VISITOR,
        ?int $userId = null,
        ?string $sessionId = null,
        ?int $viewDurationMs = null,
        ?string $deviceType = null
    ): self {
        return static::create([
            'player_id' => $playerId,
            'insight_type' => $insightType,
            'action' => $action,
            'user_type' => $userType,
            'user_id' => $userId,
            'session_id' => $sessionId ?? session()->getId(),
            'view_duration_ms' => $viewDurationMs,
            'device_type' => $deviceType,
            'ip_hash' => hash('sha256', request()->ip() ?? 'unknown'),
        ]);
    }

    /**
     * Get engagement metrics for a player
     */
    public static function getEngagementMetrics(int $playerId, int $days = 30): array
    {
        $query = static::where('player_id', $playerId)
            ->where('created_at', '>=', now()->subDays($days));

        return [
            'total_views' => $query->where('action', self::ACTION_VIEW)->count(),
            'total_expands' => $query->where('action', self::ACTION_EXPAND)->count(),
            'total_shares' => $query->where('action', self::ACTION_SHARE)->count(),
            'total_exports' => $query->where('action', self::ACTION_EXPORT)->count(),
            'unique_sessions' => $query->distinct('session_id')->count('session_id'),
            'avg_view_duration_ms' => $query->whereNotNull('view_duration_ms')->avg('view_duration_ms'),
            'expand_rate' => $query->where('action', self::ACTION_VIEW)->count() > 0
                ? ($query->where('action', self::ACTION_EXPAND)->count() / $query->where('action', self::ACTION_VIEW)->count()) * 100
                : 0,
        ];
    }

    /**
     * Get all user types
     */
    public static function getUserTypes(): array
    {
        return [
            self::USER_VISITOR => 'Visitor',
            self::USER_REGISTERED => 'Registered User',
            self::USER_ADMIN => 'Admin',
            self::USER_SCOUT => 'Scout',
        ];
    }

    /**
     * Get all actions
     */
    public static function getActions(): array
    {
        return [
            self::ACTION_VIEW => 'View',
            self::ACTION_EXPAND => 'Expand',
            self::ACTION_SHARE => 'Share',
            self::ACTION_EXPORT => 'Export',
            self::ACTION_COMPARE => 'Compare',
        ];
    }

    /**
     * Get all device types
     */
    public static function getDeviceTypes(): array
    {
        return [
            self::DEVICE_DESKTOP => 'Desktop',
            self::DEVICE_MOBILE => 'Mobile',
            self::DEVICE_TABLET => 'Tablet',
        ];
    }
}
