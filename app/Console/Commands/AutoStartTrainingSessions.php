<?php

namespace App\Console\Commands;

use App\Models\TrainingSession;
use App\Models\ActivityLog;
use App\Models\SessionInitiationLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AutoStartTrainingSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-start-training-sessions {--check-only : Only check without starting sessions} {--recover-missed : Recover sessions missed during downtime}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically start training sessions when their scheduled time is reached';

    /**
     * Track if this is a recovery run for missed sessions
     */
    protected bool $isRecovery = false;

    /**
     * Acceptable delay threshold in seconds (30 seconds)
     */
    protected const DELAY_THRESHOLD_SECONDS = 30;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('Training Session Auto-Start Monitor');
        $this->info('========================================');

        // DIAGNOSTIC: Log high-precision timestamp
        $currentTime = Carbon::now('Africa/Nairobi');
        $microtime = microtime(true);

        $this->info("Current Time (High Precision): {$currentTime->format('Y-m-d H:i:s.u')}");
        $this->info("Unix Timestamp: {$microtime}");
        Log::channel('session_automation')->info('Session automation check started', [
            'current_time' => $currentTime->toIso8601String(),
            'unix_timestamp' => $microtime,
            'timezone' => config('app.timezone'),
            'is_recovery' => $this->isRecovery,
        ]);

        // Check if this is a recovery run
        $this->isRecovery = $this->option('recover-missed');

        if ($this->isRecovery) {
            $this->info('Running in RECOVERY mode - checking for missed sessions...');
            Log::channel('session_recovery')->info('Session recovery mode activated', [
                'check_time' => $currentTime->toIso8601String(),
            ]);
        }

        // Find scheduled sessions that have reached their start time
        $query = TrainingSession::where('status', 'scheduled')
            ->where('scheduled_start_time', '<=', $currentTime);

        // DIAGNOSTIC: Log the query being executed
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        Log::channel('session_automation')->debug('Session query', [
            'sql' => $sql,
            'bindings' => $bindings,
        ]);

        $sessionsToStart = $query->get();

        $this->info("Found {$sessionsToStart->count()} session(s) ready to start");

        // DIAGNOSTIC: Log all pending sessions for debugging
        $pendingSessions = TrainingSession::where('status', 'scheduled')
            ->orderBy('scheduled_start_time')
            ->limit(10)
            ->get(['id', 'team_category', 'session_type', 'scheduled_start_time', 'status']);

        Log::channel('session_automation')->info('Pending sessions status', [
            'total_pending' => TrainingSession::where('status', 'scheduled')->count(),
            'sessions' => $pendingSessions->toArray(),
        ]);

        if ($this->option('check-only')) {
            $this->info('Check only mode - no sessions started.');
            return;
        }

        if ($sessionsToStart->isEmpty()) {
            $this->info('No sessions to start at this time.');

            // DIAGNOSTIC: Check for sessions that should have started but didn't
            $missedSessions = $this->checkForMissedSessions();
            if ($missedSessions->isNotEmpty()) {
                $this->warn("Found {$missedSessions->count()} session(s) that should have started earlier!");
                Log::channel('session_automation')->warning('Missed sessions detected', [
                    'count' => $missedSessions->count(),
                    'sessions' => $missedSessions->toArray(),
                ]);

                // Log missed sessions to the database
                foreach ($missedSessions as $session) {
                    SessionInitiationLog::create([
                        'session_id' => $session->id,
                        'scheduled_time' => $session->scheduled_start_time,
                        'actual_start_time' => null,
                        'delay_seconds' => null,
                        'initiation_type' => 'recovery',
                        'initiated_by' => $this->getSystemUserId(),
                        'status' => 'skipped',
                        'error_message' => 'Session missed during system downtime',
                        'system_time_at_execution' => $currentTime,
                        'timezone_offset' => config('app.timezone'),
                    ]);
                }
            }

            return;
        }

        $count = 0;
        $failed = 0;
        $skipped = 0;
        $delays = [];

        foreach ($sessionsToStart as $session) {
            $startTime = microtime(true);

            try {
                // DIAGNOSTIC: Log before starting
                $this->info("Attempting to start session: {$session->id} - {$session->team_category}");
                Log::channel('session_automation')->info('Session start attempt', [
                    'session_id' => $session->id,
                    'scheduled_time' => $session->scheduled_start_time->toIso8601String(),
                    'delay_seconds' => $currentTime->diffInSeconds($session->scheduled_start_time),
                ]);

                $result = $this->startSession($session);

                // Refresh the session to get updated actual_start_time
                $session->refresh();

                $endTime = microtime(true);
                $processingTimeMs = round(($endTime - $startTime) * 1000, 2);

                // Calculate delay from scheduled time
                $scheduledTime = $session->scheduled_start_time;
                $actualTime = $session->actual_start_time;
                $timeDelay = $scheduledTime->diffInSeconds($actualTime);

                $delays[] = [
                    'session_id' => $session->id,
                    'team_category' => $session->team_category,
                    'scheduled_time' => $scheduledTime->toIso8601String(),
                    'actual_time' => $actualTime->toIso8601String(),
                    'delay_seconds' => $timeDelay,
                    'processing_time_ms' => $processingTimeMs,
                ];

                // Log successful initiation to database
                SessionInitiationLog::logInitiation(
                    $session,
                    $this->isRecovery ? 'recovery' : 'scheduled',
                    'success',
                    null,
                    $processingTimeMs
                );

                $this->info("✓ Started session: {$session->team_category} {$session->session_type} (Delay: {$timeDelay}s, Processing: {$processingTimeMs}ms)");
                $count++;

            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Failed to start session {$session->id}: {$e->getMessage()}");
                Log::channel('session_automation')->error('Session start failed', [
                    'session_id' => $session->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Log failed initiation to database
                SessionInitiationLog::logInitiation(
                    $session,
                    $this->isRecovery ? 'recovery' : 'scheduled',
                    'failed',
                    $e->getMessage(),
                    round((microtime(true) - $startTime) * 1000, 2)
                );
            }
        }

        $this->info("========================================");
        $this->info("Summary: Started: {$count}, Failed: {$failed}, Skipped: {$skipped}");
        $this->info("========================================");

        // Log comprehensive session initiation event
        Log::channel('session_automation')->info('Session automation completed', [
            'check_time' => $currentTime->toIso8601String(),
            'is_recovery' => $this->isRecovery,
            'sessions_started' => $count,
            'sessions_failed' => $failed,
            'sessions_skipped' => $skipped,
            'delay_details' => $delays,
            'delay_threshold_seconds' => self::DELAY_THRESHOLD_SECONDS,
        ]);
    }

    /**
     * Start a single session with proper concurrency handling
     */
    protected function startSession(TrainingSession $session): bool
    {
        return DB::transaction(function () use ($session) {
            // Use row-level locking to prevent concurrent starts
            $lockedSession = TrainingSession::lockForUpdate()->find($session->id);

            if (!$lockedSession) {
                throw new \Exception('Session not found or was already processed');
            }

            if ($lockedSession->status !== 'scheduled') {
                throw new \Exception("Session status is '{$lockedSession->status}', not 'scheduled'");
            }

            // Check if there's already an active session for this team
            $activeSession = TrainingSession::where('team_category', $lockedSession->team_category)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if ($activeSession) {
                throw new \Exception('There is already an active session for this team.');
            }

            // Start the session
            $lockedSession->update([
                'actual_start_time' => now(),
                'started_by' => $this->getSystemUserId(),
                'status' => 'active',
            ]);

            // Log activity (handle console context where auth is not available)
            try {
                $userId = auth()->id() ?? $this->getSystemUserId();
                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => 'auto_started_training_session',
                    'model' => 'TrainingSession',
                    'model_id' => $lockedSession->id,
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ?? 'System',
                    'metadata' => [
                        'team_category' => $lockedSession->team_category,
                        'session_type' => $lockedSession->session_type,
                        'scheduled_start_time' => $lockedSession->scheduled_start_time->toIso8601String(),
                        'actual_start_time' => $lockedSession->actual_start_time->toIso8601String(),
                        'delay_seconds' => $lockedSession->scheduled_start_time->diffInSeconds($lockedSession->actual_start_time),
                        'is_recovery' => $this->isRecovery,
                    ]
                ]);
            } catch (\Exception $e) {
                // Log to file only if activity log fails
                Log::channel('session_automation')->warning('Activity log failed', [
                    'error' => $e->getMessage(),
                    'session_id' => $lockedSession->id,
                ]);
            }

            return true;
        });
    }

    /**
     * Get system user ID for automated actions
     */
    protected function getSystemUserId(): int
    {
        $adminUser = \App\Models\User::where('user_type', 'admin')->first();
        return $adminUser ? $adminUser->id : 1;
    }

    /**
     * Check for sessions that should have started but didn't
     */
    protected function checkForMissedSessions()
    {
        $gracePeriodMinutes = 5; // Allow 5 minute grace period

        return TrainingSession::where('status', 'scheduled')
            ->where('scheduled_start_time', '<', now()->subMinutes($gracePeriodMinutes))
            ->get();
    }
}
