<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AI Insights Job Model
 *
 * Tracks scheduled job execution for AI insights system.
 * Manages the automated update protocol and data refresh cycles.
 *
 * @property int $id
 * @property string $job_type
 * @property string $job_status
 * @property array|null $job_config
 * @property array|null $job_result
 * @property string|null $error_message
 * @property array|null $affected_players
 * @property int $players_processed
 * @property int $insights_generated
 * @property int|null $execution_time_ms
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon|null $next_scheduled_at
 * @property bool $is_recurring
 * @property string $recurrence_pattern
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AiInsightsJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_type',
        'job_status',
        'job_config',
        'job_result',
        'error_message',
        'affected_players',
        'players_processed',
        'insights_generated',
        'execution_time_ms',
        'started_at',
        'completed_at',
        'next_scheduled_at',
        'is_recurring',
        'recurrence_pattern',
    ];

    protected $casts = [
        'job_config' => 'array',
        'job_result' => 'array',
        'affected_players' => 'array',
        'players_processed' => 'integer',
        'insights_generated' => 'integer',
        'execution_time_ms' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_scheduled_at' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    /**
     * Job type constants
     */
    public const TYPE_REFRESH = 'refresh';
    public const TYPE_GENERATE = 'generate';
    public const TYPE_SYNC = 'sync';
    public const TYPE_ARCHIVE = 'archive';
    public const TYPE_BACKUP = 'backup';

    /**
     * Job status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_RUNNING = 'running';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Default recurrence pattern (every Friday at 2:00 AM)
     */
    public const DEFAULT_RECURRENCE = 'weekly_friday_2am';

    /**
     * Scope for pending jobs
     */
    public function scopePending($query)
    {
        return $query->where('job_status', self::STATUS_PENDING);
    }

    /**
     * Scope for running jobs
     */
    public function scopeRunning($query)
    {
        return $query->where('job_status', self::STATUS_RUNNING);
    }

    /**
     * Scope for completed jobs
     */
    public function scopeCompleted($query)
    {
        return $query->where('job_status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for failed jobs
     */
    public function scopeFailed($query)
    {
        return $query->where('job_status', self::STATUS_FAILED);
    }

    /**
     * Scope by job type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('job_type', $type);
    }

    /**
     * Scope for scheduled jobs
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('next_scheduled_at')
            ->where('next_scheduled_at', '>', now());
    }

    /**
     * Mark job as started
     */
    public function markAsStarted(): void
    {
        $this->update([
            'job_status' => self::STATUS_RUNNING,
            'started_at' => now(),
        ]);
    }

    /**
     * Mark job as completed
     */
    public function markAsCompleted(array $result = []): void
    {
        $this->update([
            'job_status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'execution_time_ms' => $this->started_at ? now()->diffInMilliseconds($this->started_at) : null,
            'job_result' => $result,
        ]);
    }

    /**
     * Mark job as failed
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'job_status' => self::STATUS_FAILED,
            'completed_at' => now(),
            'execution_time_ms' => $this->started_at ? now()->diffInMilliseconds($this->started_at) : null,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Update progress
     */
    public function updateProgress(int $playersProcessed, int $insightsGenerated): void
    {
        $this->update([
            'players_processed' => $playersProcessed,
            'insights_generated' => $insightsGenerated,
        ]);
    }

    /**
     * Schedule next run
     */
    public function scheduleNextRun(?\Carbon\Carbon $nextRun = null): void
    {
        $this->update([
            'next_scheduled_at' => $nextRun ?? $this->calculateNextRun(),
        ]);
    }

    /**
     * Calculate next run time based on recurrence pattern
     */
    public function calculateNextRun(): \Carbon\Carbon
    {
        return match ($this->recurrence_pattern) {
            self::DEFAULT_RECURRENCE => now()->next('Friday')->setHour(2)->setMinute(0),
            'daily_2am' => now()->addDay()->setHour(2)->setMinute(0),
            'weekly_monday_6am' => now()->next('Monday')->setHour(6)->setMinute(0),
            default => now()->addWeek()->setHour(2)->setMinute(0),
        };
    }

    /**
     * Check if job is overdue
     */
    public function isOverdue(): bool
    {
        return $this->next_scheduled_at && now()->isAfter($this->next_scheduled_at);
    }

    /**
     * Get all job types
     */
    public static function getJobTypes(): array
    {
        return [
            self::TYPE_REFRESH => 'Data Refresh',
            self::TYPE_GENERATE => 'Generate Insights',
            self::TYPE_SYNC => 'Data Sync',
            self::TYPE_ARCHIVE => 'Archive Old Data',
            self::TYPE_BACKUP => 'Backup Insights',
        ];
    }

    /**
     * Get all job statuses
     */
    public static function getJobStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_RUNNING => 'Running',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get all recurrence patterns
     */
    public static function getRecurrencePatterns(): array
    {
        return [
            self::DEFAULT_RECURRENCE => 'Weekly (Friday 2:00 AM)',
            'daily_2am' => 'Daily (2:00 AM)',
            'weekly_monday_6am' => 'Weekly (Monday 6:00 AM)',
            'manual' => 'Manual Only',
        ];
    }
}
