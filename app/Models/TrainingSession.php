<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_type',
        'team_category',
        'scheduled_start_time',
        'actual_start_time',
        'end_time',
        'started_by',
        'status',
        'total_duration_minutes',
        'players_admitted',
        'late_arrivals',
    ];

    protected $casts = [
        'scheduled_start_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function startedBy()
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForTeam($query, $teamCategory)
    {
        return $query->where('team_category', $teamCategory);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Methods
    public function start($userId)
    {
        if ($this->status !== 'scheduled') {
            throw new \Exception('Session can only be started if it is scheduled.');
        }

        // Check if there's already an active session for this team
        $activeSession = self::where('team_category', $this->team_category)
            ->where('status', 'active')
            ->first();

        if ($activeSession) {
            throw new \Exception('There is already an active session for this team.');
        }

        $this->update([
            'actual_start_time' => now(),
            'started_by' => $userId,
            'status' => 'active',
        ]);

        return $this;
    }

    public function end()
    {
        if ($this->status !== 'active') {
            throw new \Exception('Session can only be ended if it is active.');
        }

        $endTime = now();
        $duration = $this->actual_start_time->diffInMinutes($endTime);

        $this->update([
            'end_time' => $endTime,
            'total_duration_minutes' => $duration,
            'status' => 'ended',
        ]);

        // Calculate final training times for all attendances
        $this->calculateFinalTrainingTimes();

        // Mark absent players
        $this->markAbsentPlayers();

        return $this;
    }

    public function admitPlayer(Player $player, $userId)
    {
        if ($this->status !== 'active') {
            throw new \Exception('Players can only be admitted to active sessions.');
        }

        if ($this->end_time) {
            throw new \Exception('Session has ended. No new admissions allowed.');
        }

        // Check if player is already admitted
        $existingAttendance = $this->attendances()->where('player_id', $player->id)->first();
        if ($existingAttendance) {
            throw new \Exception('Player is already admitted to this session.');
        }

        // Additional check to prevent duplicates based on player, session type, and date
        $duplicateCheck = Attendance::where('player_id', $player->id)
            ->where('session_type', $this->session_type)
            ->where('session_date', $this->scheduled_start_time->toDateString())
            ->first();

        if ($duplicateCheck) {
            throw new \Exception('Player already has attendance recorded for this date and session type.');
        }

        // When admitting a player, set check-in time to scheduled start time
        // This represents that the player was present at the scheduled time
        $checkInTime = $this->scheduled_start_time;
        $missedMinutes = 0; // Admitted players are marked as present at scheduled time
        $latenessCategory = 'on_time';

        // Create attendance record
        $attendance = Attendance::create([
            'player_id' => $player->id,
            'session_id' => $this->id,
            'session_type' => $this->session_type,
            'session_date' => $this->scheduled_start_time->toDateString(),
            'check_in_time' => $checkInTime,
            'missed_minutes' => $missedMinutes,
            'lateness_category' => $latenessCategory,
            'recorded_by' => $userId,
        ]);

        // Update session counters
        $this->increment('players_admitted');
        if ($latenessCategory !== 'on_time') {
            $this->increment('late_arrivals');
        }

        return $attendance;
    }

    private function calculateMissedMinutes($checkInTime)
    {
        $scheduledTime = Carbon::parse($this->scheduled_start_time);
        $checkIn = Carbon::parse($checkInTime);

        if ($checkIn->lte($scheduledTime)) {
            return 0; // On time or early
        }

        return $scheduledTime->diffInMinutes($checkIn);
    }

    private function determineLatenessCategory($missedMinutes)
    {
        if ($missedMinutes == 0) {
            return 'on_time';
        } elseif ($missedMinutes <= 10) {
            return 'late';
        } else {
            return 'very_late';
        }
    }

    private function calculateFinalTrainingTimes()
    {
        foreach ($this->attendances as $attendance) {
            $trainedMinutes = $this->end_time->diffInMinutes($attendance->check_in_time);
            $attendance->update(['trained_minutes' => $trainedMinutes]);
        }
    }

    private function markAbsentPlayers()
    {
        // Map session team_category to player category format
        $categoryMapping = [
            'U13' => 'u13',
            'U15' => 'u15',
            'U17' => 'u17',
            'Senior' => 'senior',
        ];

        $playerCategory = $categoryMapping[$this->team_category] ?? $this->team_category;

        // Get eligible players who don't have attendance records for this session
        $absentPlayers = Player::where('category', $playerCategory)
            ->where('registration_status', 'Active')
            ->whereDoesntHave('attendances', function ($query) {
                $query->where('session_id', $this->id);
            })
            ->get();

        foreach ($absentPlayers as $player) {
            Attendance::create([
                'player_id' => $player->id,
                'session_id' => $this->id,
                'session_type' => $this->session_type,
                'session_date' => $this->scheduled_start_time->toDateString(),
                'check_in_time' => null,
                'missed_minutes' => $this->total_duration_minutes,
                'lateness_category' => 'absent',
                'trained_minutes' => 0,
                'recorded_by' => $this->started_by, // Use the session starter as recorder
            ]);
        }
    }

    // Accessors
    public function getElapsedTimeAttribute()
    {
        if (!$this->actual_start_time) {
            return 0;
        }

        $endTime = $this->end_time ?: now();
        return $this->actual_start_time->diffInMinutes($endTime);
    }

    public function getFormattedElapsedTimeAttribute()
    {
        $minutes = $this->elapsed_time;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$remainingMinutes}m";
        }

        return "{$minutes}m";
    }

    public function getPunctualityRateAttribute()
    {
        if ($this->players_admitted == 0) {
            return 0;
        }

        $onTimePlayers = $this->players_admitted - $this->late_arrivals;
        return round(($onTimePlayers / $this->players_admitted) * 100, 1);
    }
}
