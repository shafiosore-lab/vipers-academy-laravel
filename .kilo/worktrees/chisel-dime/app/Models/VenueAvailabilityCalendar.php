<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * VenueAvailabilityCalendar Model
 *
 * Tracks specific availability for tournament venues
 * Allows blocking specific dates/times for maintenance, bookings, etc.
 */
class VenueAvailabilityCalendar extends Model
{
    protected $fillable = [
        'venue_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
        'reason',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean',
    ];

    // Reason constants
    const REASON_MAINTENANCE = 'maintenance';
    const REASON_BOOKED = 'booked';
    const REASON_EVENT = 'event';
    const REASON_UNAVAILABLE = 'unavailable';
    const REASON_OTHER = 'other';

    /**
     * Get the venue this calendar entry belongs to
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(TournamentVenue::class);
    }

    /**
     * Get available reasons
     */
    public static function getReasons(): array
    {
        return [
            self::REASON_MAINTENANCE => 'Maintenance',
            self::REASON_BOOKED => 'Booked',
            self::REASON_EVENT => 'Event',
            self::REASON_UNAVAILABLE => 'Unavailable',
            self::REASON_OTHER => 'Other',
        ];
    }

    /**
     * Scope for available slots
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for unavailable slots
     */
    public function scopeUnavailable($query)
    {
        return $query->where('is_available', false);
    }

    /**
     * Scope for a specific date
     */
    public function scopeOnDate($query, string $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if this is an all-day block
     */
    public function isAllDay(): bool
    {
        return is_null($this->start_time) && is_null($this->end_time);
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        if ($this->isAllDay()) {
            return 'All Day';
        }

        $start = $this->start_time ? $this->start_time->format('H:i') : '00:00';
        $end = $this->end_time ? $this->end_time->format('H:i') : '23:59';

        return "{$start} - {$end}";
    }

    /**
     * Get reason label
     */
    public function getReasonLabelAttribute(): string
    {
        return self::getReasons()[$this->reason] ?? $this->reason;
    }
}
