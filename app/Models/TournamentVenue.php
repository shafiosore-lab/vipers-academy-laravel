<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TournamentVenue Model
 *
 * Represents a venue specific to a tournament
 * Used for venue assignment during match scheduling
 * Enhanced with facilities, amenities, photos, and booking management
 */
class TournamentVenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
        'address',
        'city',
        'country',
        'capacity',
        'surface_type',
        'is_active',
        'display_order',
        // Enhanced fields
        'latitude',
        'longitude',
        'timezone',
        'contact_name',
        'contact_phone',
        'contact_email',
        'facilities',
        'amenities',
        'photos',
        'main_photo',
        'length_meters',
        'width_meters',
        'has_parking',
        'has_accessibility',
        'is_accessible',
        'is_available',
        'hourly_rate',
        'booking_contact',
        'has_medical_facility',
        'has_security',
        'last_inspection_date',
        'safety_certificate',
        'description',
        'operating_hours',
        'restrictions',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'facilities' => 'array',
        'amenities' => 'array',
        'photos' => 'array',
        'length_meters' => 'integer',
        'width_meters' => 'integer',
        'has_parking' => 'boolean',
        'has_accessibility' => 'boolean',
        'is_accessible' => 'boolean',
        'is_available' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'has_medical_facility' => 'boolean',
        'has_security' => 'boolean',
        'last_inspection_date' => 'date',
        'operating_hours' => 'array',
        'restrictions' => 'array',
    ];

    // Surface type constants
    const SURFACE_GRASS = 'grass';
    const SURFACE_ARTIFICIAL = 'artificial';
    const SURFACE_INDOOR = 'indoor';
    const SURFACE_HYBRID = 'hybrid';

    /**
     * Standard facility options
     */
    public const FACILITY_TYPES = [
        'changing_rooms' => 'Changing Rooms',
        'showers' => 'Showers',
        'restrooms' => 'Restrooms',
        'parking' => 'Parking',
        'floodlights' => 'Floodlights',
        'scoreboard' => 'Electronic Scoreboard',
        'video_screens' => 'Video Screens',
        'sound_system' => 'Sound System',
        'press_box' => 'Press Box',
        'vip_lounge' => 'VIP Lounge',
        'players_tunnel' => 'Players Tunnel',
        'dugouts' => 'Dugouts',
        'medical_room' => 'Medical Room',
        'doping_control' => 'Doping Control Room',
        'storage' => 'Equipment Storage',
        'training_facilities' => 'Training Facilities',
    ];

    /**
     * Standard amenity options
     */
    public const AMENITY_TYPES = [
        'var' => 'VAR Technology',
        'goal_line_technology' => 'Goal Line Technology',
        'hawk_eye' => 'Hawk-Eye',
        'var_monitor' => 'VAR Monitor',
        'wifi' => 'WiFi',
        'broadcasting' => 'Broadcasting Facilities',
        'media_center' => 'Media Center',
        'hospitality_suites' => 'Hospitality Suites',
        'catering' => 'Catering Facilities',
        'merchandise' => 'Merchandise Stalls',
        'first_aid' => 'First Aid Station',
        'ambulance_access' => 'Ambulance Access',
        'helipad' => 'Helipad',
        'hotel_nearby' => 'Nearby Hotel',
        'restaurant' => 'Restaurant/Cafe',
    ];

    /**
     * Get available surface types
     */
    public static function getSurfaceTypes(): array
    {
        return [
            self::SURFACE_GRASS => 'Natural Grass',
            self::SURFACE_ARTIFICIAL => 'Artificial Turf',
            self::SURFACE_INDOOR => 'Indoor Court',
            self::SURFACE_HYBRID => 'Hybrid Surface',
        ];
    }

    /**
     * Get the tournament this venue belongs to
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get matches scheduled at this venue
     */
    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'venue_id');
    }

    /**
     * Get availability calendar entries
     */
    public function availabilityCalendar(): HasMany
    {
        return $this->hasMany(VenueAvailabilityCalendar::class);
    }

    /**
     * Get booking requests
     */
    public function bookingRequests(): HasMany
    {
        return $this->hasMany(VenueBookingRequest::class);
    }

    /**
     * Scope for active venues
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for available venues
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for ordered venues
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Scope for a specific tournament
     */
    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    /**
     * Scope for venues with parking
     */
    public function scopeWithParking($query)
    {
        return $query->where('has_parking', true);
    }

    /**
     * Scope for accessible venues
     */
    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }

    /**
     * Get available slots for a given date
     */
    public function getAvailableSlots($date, $matchDuration = 90): array
    {
        // Default slots: 8am to 8pm
        $slots = [];
        $startHour = 8;
        $endHour = 20;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slotStart = \Carbon\Carbon::parse($date)->setHour($hour)->setMinute(0);
            $slotEnd = $slotStart->copy()->addMinutes($matchDuration);

            // Check if slot conflicts with existing matches
            $hasConflict = $this->matches()
                ->whereDate('kickoff_time', $date)
                ->where(function ($query) use ($slotStart, $slotEnd) {
                    $query->where(function ($q) use ($slotStart, $slotEnd) {
                        $q->where('kickoff_time', '<', $slotEnd)
                          ->whereRaw('DATE_ADD(kickoff_time, INTERVAL duration MINUTE) > ?', [$slotStart]);
                    });
                })
                ->exists();

            $slots[] = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'available' => !$hasConflict,
            ];
        }

        return $slots;
    }

    /**
     * Check if venue is available at given time
     */
    public function isAvailableAt($dateTime, $duration = 90): bool
    {
        // Check general availability flag
        if (!$this->is_available) {
            return false;
        }

        $endTime = \Carbon\Carbon::parse($dateTime)->addMinutes($duration);

        return !$this->matches()
            ->whereDate('kickoff_time', \Carbon\Carbon::parse($dateTime)->toDateString())
            ->where(function ($query) use ($dateTime, $endTime) {
                $query->where('kickoff_time', '<', $endTime)
                      ->whereRaw('DATE_ADD(kickoff_time, INTERVAL duration MINUTE) > ?', [$dateTime]);
            })
            ->exists();
    }

    /**
     * Check if venue is available on a specific date with time slot checking
     */
    public function isAvailableOn(string $date, ?string $startTime = null, ?string $endTime = null): bool
    {
        // Check general availability
        if (!$this->is_available) {
            return false;
        }

        // Check calendar availability
        $calendarEntry = $this->availabilityCalendar()
            ->where('date', $date)
            ->first();

        if ($calendarEntry && !$calendarEntry->is_available) {
            return false;
        }

        // Check for conflicting matches
        $query = $this->matches()
            ->where('match_date', $date)
            ->where('status', '!=', 'cancelled');

        if ($startTime && $endTime) {
            $query->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });
        }

        return !$query->exists();
    }

    /**
     * Get formatted facilities list
     */
    public function getFacilityList(): array
    {
        $facilities = $this->facilities ?? [];
        return array_map(function ($facility) {
            return self::FACILITY_TYPES[$facility] ?? $facility;
        }, $facilities);
    }

    /**
     * Get formatted amenities list
     */
    public function getAmenityList(): array
    {
        $amenities = $this->amenities ?? [];
        return array_map(function ($amenity) {
            return self::AMENITY_TYPES[$amenity] ?? $amenity;
        }, $amenities);
    }

    /**
     * Check if venue has specific facility
     */
    public function hasFacility(string $facility): bool
    {
        return in_array($facility, $this->facilities ?? []);
    }

    /**
     * Check if venue has specific amenity
     */
    public function hasAmenity(string $amenity): bool
    {
        return in_array($amenity, $this->amenities ?? []);
    }

    /**
     * Add a photo to the venue
     */
    public function addPhoto(string $url, ?string $caption = null): void
    {
        $photos = $this->photos ?? [];
        $photos[] = [
            'url' => $url,
            'caption' => $caption,
            'added_at' => now()->toISOString(),
        ];
        $this->photos = $photos;
        $this->save();
    }

    /**
     * Remove a photo from the venue
     */
    public function removePhoto(int $index): void
    {
        $photos = $this->photos ?? [];
        if (isset($photos[$index])) {
            unset($photos[$index]);
            $this->photos = array_values($photos);
            $this->save();
        }
    }

    /**
     * Set main photo
     */
    public function setMainPhoto(string $url): void
    {
        $this->main_photo = $url;

        // Add to photos array if not already there
        $photos = $this->photos ?? [];
        $found = false;
        foreach ($photos as $photo) {
            if ($photo['url'] === $url) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->addPhoto($url);
        }

        $this->save();
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get display name with location
     */
    public function getDisplayName(): string
    {
        $location = $this->city ?? 'TBD';
        return "{$this->name} ({$location})";
    }

    /**
     * Check if venue has location coordinates
     */
    public function hasLocation(): bool
    {
        return !empty($this->latitude) && !empty($this->longitude);
    }

    /**
     * Get location array for API/JSON
     */
    public function getLocationArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'timezone' => $this->timezone ?? 'UTC',
            'address' => $this->full_address,
        ];
    }

    /**
     * Calculate distance to another venue (in km)
     */
    public function distanceTo(TournamentVenue $other): ?float
    {
        if (!$this->hasLocation() || !$other->hasLocation()) {
            return null;
        }

        // Haversine formula
        $lat1 = deg2rad($this->latitude);
        $lat2 = deg2rad($other->latitude);
        $deltaLat = deg2rad($other->latitude - $this->latitude);
        $deltaLon = deg2rad($other->longitude - $this->longitude);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round(6371 * $c, 2); // Earth's radius in km
    }

    /**
     * Calculate distance from a coordinate point (in km)
     */
    public function distanceFrom(float $lat, float $lng): ?float
    {
        if (!$this->hasLocation()) {
            return null;
        }

        // Haversine formula
        $lat1 = deg2rad($this->latitude);
        $lat2 = deg2rad($lat);
        $deltaLat = deg2rad($lat - $this->latitude);
        $deltaLon = deg2rad($lng - $this->longitude);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round(6371 * $c, 2);
    }

    /**
     * Get upcoming matches at this venue
     */
    public function upcomingMatches(int $limit = 5)
    {
        return $this->matches()
            ->where('match_date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Get matches on a specific date
     */
    public function matchesOnDate(string $date)
    {
        return $this->matches()
            ->where('match_date', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get pitch dimensions formatted
     */
    public function getPitchDimensionsAttribute(): ?string
    {
        if (!$this->length_meters || !$this->width_meters) {
            return null;
        }

        return $this->length_meters . 'm x ' . $this->width_meters . 'm';
    }

    /**
     * Get surface type label
     */
    public function getSurfaceTypeLabelAttribute(): string
    {
        return self::getSurfaceTypes()[$this->surface_type] ?? $this->surface_type;
    }

    /**
     * Get formatted capacity
     */
    public function getFormattedCapacityAttribute(): string
    {
        return number_format($this->capacity) . ' seats';
    }

    /**
     * Check if venue needs safety inspection
     */
    public function needsInspection(int $monthsThreshold = 12): bool
    {
        if (!$this->last_inspection_date) {
            return true;
        }

        return $this->last_inspection_date->diffInMonths(now()) > $monthsThreshold;
    }

    /**
     * Get operating hours for a specific day
     */
    public function getOperatingHoursForDay(string $day): ?array
    {
        $hours = $this->operating_hours ?? [];
        return $hours[$day] ?? null;
    }

    /**
     * Check if venue is open at a specific time
     */
    public function isOpenAt(string $day, string $time): bool
    {
        $hours = $this->getOperatingHoursForDay($day);

        if (!$hours || !isset($hours['open']) || !isset($hours['close'])) {
            return true; // Default to open if not specified
        }

        return $time >= $hours['open'] && $time <= $hours['close'];
    }

    /**
     * Get list of pending booking requests
     */
    public function pendingBookings()
    {
        return $this->bookingRequests()
            ->where('status', 'pending')
            ->orderBy('requested_date')
            ->get();
    }

    /**
     * Get total bookings count
     */
    public function getTotalBookingsAttribute(): int
    {
        return $this->bookingRequests()->count();
    }

    /**
     * Get approved bookings count
     */
    public function getApprovedBookingsAttribute(): int
    {
        return $this->bookingRequests()->where('status', 'approved')->count();
    }
}
