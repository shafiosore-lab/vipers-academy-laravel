<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class SessionInitiationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'scheduled_time',
        'actual_start_time',
        'delay_seconds',
        'initiation_type', // 'scheduled', 'manual', 'recovery'
        'initiated_by',
        'status', // 'success', 'failed', 'skipped'
        'error_message',
        'processing_time_ms',
        'system_time_at_execution',
        'timezone_offset',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'system_time_at_execution' => 'datetime',
        'delay_seconds' => 'integer',
        'processing_time_ms' => 'integer',
    ];

    /**
     * Get the training session associated with this log
     */
    public function session()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    /**
     * Get the user who initiated the session
     */
    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    /**
     * Scope for successful initiations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed initiations
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for recovery initiations
     */
    public function scopeRecovery($query)
    {
        return $query->where('initiation_type', 'recovery');
    }

    /**
     * Scope for initiations with significant delay
     */
    public function scopeWithDelay($query, $thresholdSeconds = 60)
    {
        return $query->where('delay_seconds', '>', $thresholdSeconds);
    }

    /**
     * Get formatted delay
     */
    public function getFormattedDelayAttribute()
    {
        $seconds = $this->delay_seconds ?? 0;

        if ($seconds < 60) {
            return "{$seconds}s";
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes < 60) {
            return "{$minutes}m {$remainingSeconds}s";
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return "{$hours}h {$remainingMinutes}m";
    }

    /**
     * Get delay severity level
     */
    public function getDelaySeverityAttribute()
    {
        $seconds = $this->delay_seconds ?? 0;

        if ($seconds <= 30) {
            return 'normal'; // Within acceptable threshold
        } elseif ($seconds <= 300) {
            return 'warning'; // 5 minutes - needs attention
        } else {
            return 'critical'; // Significant delay
        }
    }

    /**
     * Create a log entry for session initiation
     */
    public static function logInitiation(
        TrainingSession $session,
        string $type,
        string $status,
        ?string $errorMessage = null,
        ?int $processingTimeMs = null
    ): self {
        $systemTime = now();
        $scheduledTime = $session->scheduled_start_time;
        $delaySeconds = $status === 'success'
            ? $systemTime->diffInSeconds($scheduledTime)
            : null;

        return static::create([
            'session_id' => $session->id,
            'scheduled_time' => $scheduledTime,
            'actual_start_time' => $status === 'success' ? $systemTime : null,
            'delay_seconds' => $delaySeconds,
            'initiation_type' => $type,
            'initiated_by' => $session->started_by,
            'status' => $status,
            'error_message' => $errorMessage,
            'processing_time_ms' => $processingTimeMs,
            'system_time_at_execution' => $systemTime,
            'timezone_offset' => config('app.timezone'),
        ]);
    }

    /**
     * Get statistics for a date range
     */
    public static function getStatistics(Carbon $startDate, Carbon $endDate): array
    {
        $query = static::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_initiations' => $query->count(),
            'successful' => $query->successful()->count(),
            'failed' => $query->failed()->count(),
            'recovery_count' => $query->recovery()->count(),
            'avg_delay_seconds' => round($query->successful()->avg('delay_seconds') ?? 0, 2),
            'max_delay_seconds' => $query->successful()->max('delay_seconds') ?? 0,
            'delayed_sessions' => $query->successful()->withDelay()->count(),
        ];
    }
}
