<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiInsight;
use App\Models\AiInsightsAnalytic;
use App\Models\AiInsightsDataSource;
use App\Models\AiInsightsMetric;
use App\Services\AiInsightsGenerator;
use App\Services\AiInsightsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * AI Insights API Controller
 *
 * RESTful API endpoints for AI Insights system.
 * Provides microservices-ready architecture with clear API contracts.
 *
 * Endpoints:
 * GET    /api/players/{player}/ai-insights      - Get all insights for a player
 * GET    /api/players/{player}/ai-insights/{type} - Get specific insight type
 * POST   /api/players/{player}/ai-insights/refresh - Trigger insights refresh
 * GET    /api/players/{player}/ai-insights/freshness - Get data freshness status
 * GET    /api/players/{player}/ai-insights/metrics - Get engagement metrics
 * POST   /api/data-sources/{source}/upload - Record data upload
 * GET    /api/ai-insights/system/status - Get system status
 */
class AiInsightsController extends Controller
{
    public function __construct(
        protected AiInsightsService $insightsService,
        protected AiInsightsGenerator $insightsGenerator
    ) {}

    /**
     * Get all insights for a player
     */
    public function getPlayerInsights(Request $request, int $playerId): JsonResponse
    {
        try {
            $useCache = !$request->boolean('no_cache', false);
            $insights = $this->insightsService->getPlayerInsights($playerId, $useCache);

            // Record view analytic
            $this->recordAnalytic($playerId, AiInsight::TYPE_STRENGTH, AiInsightsAnalytic::ACTION_VIEW, $request);

            return response()->json([
                'success' => true,
                'data' => [
                    'player_id' => $playerId,
                    'insights' => $insights,
                    'insight_types' => AiInsight::getInsightTypes(),
                    'cached' => $useCache,
                ],
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                    'version' => '1.0',
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve insights',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get specific insight type for a player
     */
    public function getInsightByType(Request $request, int $playerId, string $type): JsonResponse
    {
        try {
            $insight = $this->insightsService->getInsightsByType($playerId, $type);

            if (!$insight) {
                return response()->json([
                    'success' => false,
                    'error' => 'Insight not found',
                    'message' => "No active insight of type '{$type}' found for this player",
                ], 404);
            }

            // Record analytic
            $this->recordAnalytic($playerId, $type, AiInsightsAnalytic::ACTION_VIEW, $request);

            return response()->json([
                'success' => true,
                'data' => $insight,
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve insight',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Trigger insights refresh for a player
     */
    public function refreshInsights(Request $request, int $playerId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'force' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'messages' => $validator->errors(),
                ], 422);
            }

            $force = $request->boolean('force', false);

            $startTime = microtime(true);
            $insights = $this->insightsGenerator->generatePlayerInsights($playerId, $force);
            $executionTime = (microtime(true) - $startTime) * 1000;

            // Clear cache
            $this->insightsService->clearCache($playerId);

            return response()->json([
                'success' => true,
                'data' => [
                    'player_id' => $playerId,
                    'insights_generated' => count($insights),
                    'execution_time_ms' => round($executionTime, 2),
                    'forced' => $force,
                ],
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to refresh insights',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get data freshness status for a player
     */
    public function getDataFreshness(int $playerId): JsonResponse
    {
        try {
            $freshness = $this->insightsService->getDataFreshness($playerId);

            return response()->json([
                'success' => true,
                'data' => $freshness,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get freshness',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get engagement metrics for a player
     */
    public function getEngagementMetrics(Request $request, int $playerId): JsonResponse
    {
        try {
            $days = $request->integer('days', 30);
            $metrics = $this->insightsService->getEngagementMetrics($playerId, $days);

            return response()->json([
                'success' => true,
                'data' => $metrics,
                'meta' => [
                    'period_days' => $days,
                    'generated_at' => now()->toIso8601String(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get metrics',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Record data upload for a data source
     */
    public function recordDataUpload(Request $request, int $sourceId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'data_points' => 'integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'messages' => $validator->errors(),
                ], 422);
            }

            $dataPoints = $request->integer('data_points', 0);
            $this->insightsService->recordDataUpload($sourceId, $dataPoints);

            return response()->json([
                'success' => true,
                'data' => [
                    'source_id' => $sourceId,
                    'data_points_recorded' => $dataPoints,
                    'recorded_at' => now()->toIso8601String(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to record data upload',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a new data source for a player
     */
    public function registerDataSource(Request $request, int $playerId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:' . implode(',', AiInsightsDataSource::getSourceTypes()),
                'name' => 'required|string|max:255',
                'identifier' => 'nullable|string|max:255',
                'auto_sync' => 'boolean',
                'sync_frequency' => 'nullable|string|in:' . implode(',', AiInsightsDataSource::getSyncFrequencies()),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'messages' => $validator->errors(),
                ], 422);
            }

            $source = $this->insightsService->registerDataSource(
                $playerId,
                $request->type,
                $request->name,
                $request->identifier,
                null,
                $request->boolean('auto_sync', true),
                $request->get('sync_frequency', AiInsightsDataSource::FREQUENCY_DAILY)
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'source_id' => $source->id,
                    'player_id' => $playerId,
                    'type' => $source->source_type,
                    'name' => $source->source_name,
                    'auto_sync' => $source->auto_sync_enabled,
                    'sync_frequency' => $source->sync_frequency,
                    'created_at' => $source->created_at->toIso8601String(),
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to register data source',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system status overview
     */
    public function getSystemStatus(): JsonResponse
    {
        try {
            $status = $this->insightsService->getSystemStatus();
            $performanceMetrics = $this->insightsService->getPerformanceMetrics();

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => $status,
                    'performance' => $performanceMetrics,
                ],
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                    'version' => '1.0',
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get system status',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Record analytic event
     */
    protected function recordAnalytic(int $playerId, string $insightType, string $action, Request $request): void
    {
        try {
            $userType = match (true) {
                $request->user() && $request->user()->isAdmin() => AiInsightsAnalytic::USER_ADMIN,
                $request->user() => AiInsightsAnalytic::USER_REGISTERED,
                default => AiInsightsAnalytic::USER_VISITOR,
            };

            AiInsightsAnalytic::record(
                $playerId,
                $insightType,
                $action,
                $userType,
                $request->user()?->id,
                $request->session()->getId(),
                null,
                $this->getDeviceType($request)
            );
        } catch (\Exception $e) {
            // Don't fail the request if analytics recording fails
            report($e);
        }
    }

    /**
     * Get device type from request
     */
    protected function getDeviceType(Request $request): string
    {
        $userAgent = $request->userAgent() ?? '';

        if (preg_match('/mobile/i', $userAgent)) {
            return AiInsightsAnalytic::DEVICE_MOBILE;
        } elseif (preg_match('/tablet/i', $userAgent)) {
            return AiInsightsAnalytic::DEVICE_TABLET;
        }

        return AiInsightsAnalytic::DEVICE_DESKTOP;
    }
}
