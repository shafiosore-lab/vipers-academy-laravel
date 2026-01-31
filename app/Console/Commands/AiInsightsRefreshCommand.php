<?php

namespace App\Console\Commands;

use App\Models\AiInsightsDataSource;
use App\Models\AiInsightsJob;
use App\Models\Player;
use App\Services\AiInsightsGenerator;
use App\Services\AiInsightsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * AI Insights Refresh Command
 *
 * Scheduled job that triggers every Friday at a configurable time (default: 2:00 AM).
 * Checks if new AI insights data has been uploaded for any players.
 * If no new data has been uploaded within the past 7 days, initiates a data refresh cycle.
 *
 * Configuration:
 * - Default schedule: Fridays at 2:00 AM
 * - Configurable via AI_INSIGHTS_REFRESH_TIME env variable
 * - Pattern: weekly_friday_2am (configurable)
 *
 * Usage:
 * php artisan ai:insights:refresh           # Run scheduled refresh
 * php artisan ai:insights:refresh --force   # Force refresh for all players
 * php artisan ai:insights:refresh --player=1 # Refresh specific player only
 */
class AiInsightsRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ai:insights:refresh
                            {--force : Force refresh for all players regardless of data changes}
                            {--player= : Specific player ID to refresh}
                            {--dry-run : Preview what would be done without making changes}';

    /**
     * The console command description.
     */
    protected $description = 'Refresh AI insights for players based on new data or scheduled refresh';

    /**
     * Default refresh time configuration
     */
    protected const DEFAULT_REFRESH_TIME = 'weekly_friday_2am';

    public function __construct(
        protected AiInsightsService $insightsService,
        protected AiInsightsGenerator $insightsGenerator
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startTime = microtime(true);
        $forceRefresh = $this->option('force');
        $playerId = $this->option('player');
        $dryRun = $this->option('dry-run');

        $this->info("Starting AI Insights Refresh...");
        $this->info("Mode: " . ($forceRefresh ? 'Force Refresh' : 'Smart Refresh'));

        // Create job record
        $job = $this->createJobRecord($playerId);

        try {
            // Get players to process
            $players = $this->getPlayersToProcess($playerId);
            $totalPlayers = $players->count();

            $this->info("Found {$totalPlayers} player(s) to process");

            if ($dryRun) {
                $this->warn("DRY RUN MODE - No changes will be made");
                $this->line("Players that would be processed:");
                foreach ($players as $player) {
                    $needsRefresh = $this->needsRefresh($player->id);
                    $this->line("  - {$player->name} (ID: {$player->id}) - " .
                        ($needsRefresh || $forceRefresh ? 'WOULD REFRESH' : 'SKIPPED'));
                }

                if ($job) {
                    $job->update(['job_status' => AiInsightsJob::STATUS_CANCELLED]);
                }

                return Command::SUCCESS;
            }

            $job?->markAsStarted();

            $playersProcessed = 0;
            $insightsGenerated = 0;
            $failedPlayers = [];

            $progressBar = $this->output->createProgressBar($totalPlayers);
            $progressBar->start();

            foreach ($players as $player) {
                try {
                    // Check if refresh is needed
                    $needsRefresh = $this->needsRefresh($player->id);

                    if (!$needsRefresh && !$forceRefresh) {
                        $this->line("  Skipping {$player->name} - no new data and not stale");
                        continue;
                    }

                    // Generate insights
                    $generatedInsights = $this->insightsGenerator->generatePlayerInsights($player->id, $forceRefresh);
                    $insightsGenerated += count($generatedInsights);

                    // Clear cache
                    $this->insightsService->clearCache($player->id);

                    $playersProcessed++;

                    // Record metrics
                    $this->recordRefreshMetrics($player->id, count($generatedInsights));

                } catch (\Exception $e) {
                    Log::error("Failed to refresh insights for player {$player->name} (ID: {$player->id}): " . $e->getMessage());
                    $failedPlayers[] = [
                        'player_id' => $player->id,
                        'name' => $player->name,
                        'error' => $e->getMessage(),
                    ];
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            // Update job record
            $executionTime = (microtime(true) - $startTime) * 1000;
            $job?->markAsCompleted([
                'players_processed' => $playersProcessed,
                'insights_generated' => $insightsGenerated,
                'failed_players' => $failedPlayers,
                'execution_time_ms' => round($executionTime, 2),
            ]);

            // Schedule next run
            $this->scheduleNextRun($job);

            // Summary
            $this->info("Refresh completed successfully!");
            $this->info("  - Players processed: {$playersProcessed}/{$totalPlayers}");
            $this->info("  - Insights generated: {$insightsGenerated}");
            $this->info("  - Execution time: " . round($executionTime / 1000, 2) . " seconds");

            if (!empty($failedPlayers)) {
                $this->warn("  - Failed players: " . count($failedPlayers));
                foreach ($failedPlayers as $failed) {
                    $this->error("    - {$failed['name']}: {$failed['error']}");
                }
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error("AI Insights Refresh failed: " . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
            ]);

            $job?->markAsFailed($e->getMessage());

            $this->error("Refresh failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Create a job record for tracking
     */
    protected function createJobRecord(?int $playerId): ?AiInsightsJob
    {
        try {
            return AiInsightsJob::create([
                'job_type' => AiInsightsJob::TYPE_REFRESH,
                'job_status' => AiInsightsJob::STATUS_PENDING,
                'job_config' => [
                    'force_refresh' => $this->option('force'),
                    'specific_player' => $playerId,
                    'dry_run' => $this->option('dry-run'),
                ],
                'is_recurring' => true,
                'recurrence_pattern' => config('ai_insights.refresh_pattern', self::DEFAULT_REFRESH_TIME),
                'next_scheduled_at' => $this->calculateNextRun(),
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to create job record: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get players to process
     */
    protected function getPlayersToProcess(?int $playerId)
    {
        if ($playerId) {
            return Player::where('id', $playerId)->get();
        }

        return Player::whereHas('gameStats')->get();
    }

    /**
     * Check if a player needs refresh
     */
    protected function needsRefresh(int $playerId): bool
    {
        return $this->insightsService->insightsNeedRefresh($playerId);
    }

    /**
     * Record refresh metrics
     */
    protected function recordRefreshMetrics(int $playerId, int $insightsCount): void
    {
        $this->insightsService->recordMetric(
            AiInsightsMetric::TYPE_INSIGHTS_GENERATED,
            'refresh_generated',
            $insightsCount,
            'count',
            $playerId
        );
    }

    /**
     * Calculate next scheduled run time
     */
    protected function calculateNextRun(): \Carbon\Carbon
    {
        $pattern = config('ai_insights.refresh_pattern', self::DEFAULT_REFRESH_TIME);

        return match ($pattern) {
            'weekly_friday_2am' => now()->next('Friday')->setHour(2)->setMinute(0),
            'daily_2am' => now()->addDay()->setHour(2)->setMinute(0),
            'weekly_monday_6am' => now()->next('Monday')->setHour(6)->setMinute(0),
            default => now()->addWeek()->setHour(2)->setMinute(0),
        };
    }

    /**
     * Schedule next run for recurring job
     */
    protected function scheduleNextRun(?AiInsightsJob $job): void
    {
        if ($job && $job->is_recurring) {
            $job->scheduleNextRun();
        }
    }
}
