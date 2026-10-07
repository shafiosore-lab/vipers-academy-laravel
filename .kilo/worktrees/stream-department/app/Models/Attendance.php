<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'session_id',
        'session_type',
        'session_date',
        'check_in_time',
        'check_out_time',
        'total_duration_minutes',
        'trained_minutes',
        'missed_minutes',
        'lateness_category',
        'recorded_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    // Relationships
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeForPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeForSessionType($query, $type)
    {
        return $query->where('session_type', $type);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('session_date', $date);
    }

    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('check_in_time');
    }

    public function scopeCheckedOut($query)
    {
        return $query->whereNotNull('check_out_time');
    }

    // Methods
    public function checkIn($recordedBy)
    {
        if ($this->check_in_time) {
            throw new \Exception('Player already checked in for this session.');
        }

        $this->update([
            'check_in_time' => now(),
            'recorded_by' => $recordedBy,
        ]);

        return $this;
    }

    public function checkOut()
    {
        if (!$this->check_in_time) {
            throw new \Exception('Player must check in before checking out.');
        }

        if ($this->check_out_time) {
            throw new \Exception('Player already checked out for this session.');
        }

        $checkOutTime = now();
        $duration = $this->calculateDuration($this->check_in_time, $checkOutTime);

        // DEBUG LOG: Track check-out and duration calculation
        \Log::debug('TIMER_DEBUG: Player checked out', [
            'attendance_id' => $this->id,
            'player_id' => $this->player_id,
            'session_id' => $this->session_id,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $checkOutTime,
            'total_duration_minutes' => $duration,
        ]);

        $this->update([
            'check_out_time' => $checkOutTime,
            'total_duration_minutes' => $duration,
        ]);

        return $this;
    }

    private function calculateDuration($checkIn, $checkOut)
    {
        // Both checkIn and checkOut are now Carbon datetime objects
        return $checkIn->diffInMinutes($checkOut);
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->total_duration_minutes) {
            return 'N/A';
        }

        $hours = floor($this->total_duration_minutes / 60);
        $minutes = $this->total_duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Get actual attendance duration considering early departures
     * This accessor prioritizes actual attendance time over scheduled duration
     */
    public function getActualDurationAttribute()
    {
        // If player has checked out, use their actual check-out time
        if ($this->check_out_time) {
            return $this->check_in_time->diffInMinutes($this->check_out_time);
        }

        // If session has ended but player didn't check out, use session end time
        if ($this->session && $this->session->end_time) {
            return $this->check_in_time->diffInMinutes($this->session->end_time);
        }

        // If session is still active, use current time
        if ($this->check_in_time) {
            return $this->check_in_time->diffInMinutes(now());
        }

        return 0;
    }
}
