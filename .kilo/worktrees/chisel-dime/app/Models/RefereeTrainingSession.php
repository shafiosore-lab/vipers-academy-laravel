<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RefereeTrainingSession Model
 *
 * Tracks training sessions for referees including
 * fitness tests, rule updates, practical assessments, etc.
 */
class RefereeTrainingSession extends Model
{
    protected $fillable = [
        'referee_id',
        'title',
        'description',
        'session_date',
        'start_time',
        'end_time',
        'location',
        'trainer_name',
        'training_type',
        'status',
        'hours_credited',
        'performance_score',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'hours_credited' => 'integer',
        'performance_score' => 'decimal:2',
    ];

    // Training type constants
    const TYPE_FITNESS = 'fitness';
    const TYPE_RULES_UPDATE = 'rules_update';
    const TYPE_PRACTICAL = 'practical';
    const TYPE_ASSESSMENT = 'assessment';
    const TYPE_SEMINAR = 'seminar';
    const TYPE_MENTORING = 'mentoring';

    // Status constants
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    /**
     * Get training types
     */
    public static function getTrainingTypes(): array
    {
        return [
            self::TYPE_FITNESS => 'Fitness Training',
            self::TYPE_RULES_UPDATE => 'Rules Update',
            self::TYPE_PRACTICAL => 'Practical Session',
            self::TYPE_ASSESSMENT => 'Assessment',
            self::TYPE_SEMINAR => 'Seminar',
            self::TYPE_MENTORING => 'Mentoring',
        ];
    }

    /**
     * Get statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_NO_SHOW => 'No Show',
        ];
    }

    /**
     * Get the referee for this training session
     */
    public function referee(): BelongsTo
    {
        return $this->belongsTo(TournamentReferee::class);
    }

    /**
     * Scope for scheduled sessions
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    /**
     * Scope for completed sessions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for upcoming sessions
     */
    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>=', now()->toDateString())
            ->where('status', self::STATUS_SCHEDULED)
            ->orderBy('session_date')
            ->orderBy('start_time');
    }

    /**
     * Scope for past sessions
     */
    public function scopePast($query)
    {
        return $query->where('session_date', '<', now()->toDateString())
            ->orderBy('session_date', 'desc');
    }

    /**
     * Scope for a specific referee
     */
    public function scopeForReferee($query, int $refereeId)
    {
        return $query->where('referee_id', $refereeId);
    }

    /**
     * Scope for a specific type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('training_type', $type);
    }

    /**
     * Mark session as completed
     */
    public function markCompleted(?float $score = null, ?string $notes = null): void
    {
        $this->status = self::STATUS_COMPLETED;

        if ($score !== null) {
            $this->performance_score = $score;
        }

        if ($notes) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') . $notes;
        }

        $this->save();
    }

    /**
     * Mark session as cancelled
     */
    public function markCancelled(?string $reason = null): void
    {
        $this->status = self::STATUS_CANCELLED;

        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') .
                "Cancellation: " . $reason;
        }

        $this->save();
    }

    /**
     * Mark session as no-show
     */
    public function markNoShow(?string $notes = null): void
    {
        $this->status = self::STATUS_NO_SHOW;

        if ($notes) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') .
                "No-show notes: " . $notes;
        }

        $this->save();
    }

    /**
     * Check if session is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->status === self::STATUS_SCHEDULED &&
               $this->session_date >= now()->toDateString();
    }

    /**
     * Check if session is past
     */
    public function isPast(): bool
    {
        return $this->session_date < now()->toDateString();
    }

    /**
     * Get training type label
     */
    public function getTrainingTypeLabelAttribute(): string
    {
        return self::getTrainingTypes()[$this->training_type] ?? $this->training_type;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        if (!$this->start_time) {
            return 'All Day';
        }

        $start = $this->start_time->format('H:i');
        $end = $this->end_time ? $this->end_time->format('H:i') : '';

        return $end ? "{$start} - {$end}" : $start;
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return (float) $this->hours_credited;
        }

        return $this->start_time->diffInMinutes($this->end_time) / 60;
    }

    /**
     * Get performance rating label
     */
    public function getPerformanceRatingLabelAttribute(): string
    {
        if (!$this->performance_score) {
            return 'N/A';
        }

        if ($this->performance_score >= 9) return 'Excellent';
        if ($this->performance_score >= 7) return 'Good';
        if ($this->performance_score >= 5) return 'Satisfactory';
        if ($this->performance_score >= 3) return 'Needs Improvement';
        return 'Poor';
    }
}
