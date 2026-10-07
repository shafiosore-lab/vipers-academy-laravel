<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * VenueBookingRequest Model
 *
 * Manages external booking requests for tournament venues
 * Allows organizations to request venue hire for events
 */
class VenueBookingRequest extends Model
{
    protected $fillable = [
        'venue_id',
        'organization_id',
        'tournament_id',
        'request_number',
        'event_name',
        'requested_date',
        'start_time',
        'end_time',
        'expected_attendance',
        'status',
        'booking_fee',
        'notes',
        'requested_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'expected_attendance' => 'integer',
        'booking_fee' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Boot method to generate request number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->request_number)) {
                $model->request_number = self::generateRequestNumber();
            }
        });
    }

    /**
     * Generate unique request number
     */
    public static function generateRequestNumber(): string
    {
        $prefix = 'VBR';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));

        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    /**
     * Get the venue for this booking
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(TournamentVenue::class);
    }

    /**
     * Get the organization that made the request
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the tournament (if any)
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the user who requested
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who approved
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for a specific venue
     */
    public function scopeForVenue($query, int $venueId)
    {
        return $query->where('venue_id', $venueId);
    }

    /**
     * Scope for a specific organization
     */
    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('requested_date', [$startDate, $endDate]);
    }

    /**
     * Scope for upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('requested_date', '>=', now()->toDateString())
            ->where('status', self::STATUS_APPROVED)
            ->orderBy('requested_date')
            ->orderBy('start_time');
    }

    /**
     * Approve the booking
     */
    public function approve(User $approvedBy, ?string $notes = null): void
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $approvedBy->id;
        $this->approved_at = now();

        if ($notes) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') .
                "Approval notes: " . $notes;
        }

        $this->save();
    }

    /**
     * Reject the booking
     */
    public function reject(User $rejectedBy, string $reason): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->approved_by = $rejectedBy->id;
        $this->approved_at = now();
        $this->notes = ($this->notes ? $this->notes . "\n\n" : '') .
            "Rejection reason: " . $reason;
        $this->save();
    }

    /**
     * Cancel the booking
     */
    public function cancel(?string $reason = null): void
    {
        $this->status = self::STATUS_CANCELLED;

        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') .
                "Cancellation reason: " . $reason;
        }

        $this->save();
    }

    /**
     * Check if booking is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if booking is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if booking can be modified
     */
    public function canBeModified(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
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
        $start = $this->start_time ? $this->start_time->format('H:i') : '';
        $end = $this->end_time ? $this->end_time->format('H:i') : '';

        return $start && $end ? "{$start} - {$end}" : 'TBD';
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInMinutes($this->end_time) / 60;
    }

    /**
     * Calculate booking fee based on hourly rate
     */
    public function calculateFee(): void
    {
        if ($this->venue && $this->venue->hourly_rate) {
            $this->booking_fee = $this->venue->hourly_rate * $this->duration_hours;
            $this->save();
        }
    }
}
