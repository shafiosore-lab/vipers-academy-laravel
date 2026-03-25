<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TournamentReferee Model
 *
 * Manages referees specific to a tournament with
 * certification levels, ratings, and match assignments
 */
class TournamentReferee extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
        'email',
        'phone',
        'license_number',
        'certification_level',
        'is_active',
        'matches_officiated',
        'rating',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'matches_officiated' => 'integer',
        'rating' => 'decimal:2',
    ];

    // Certification level constants
    const LEVEL_FIFA = 'fifa';
    const LEVEL_INTERNATIONAL = 'international';
    const LEVEL_NATIONAL = 'national';
    const LEVEL_REGIONAL = 'regional';
    const LEVEL_LOCAL = 'local';
    const LEVEL_EMERGING = 'emerging';

    /**
     * Get certification level options
     */
    public static function getCertificationLevels(): array
    {
        return [
            self::LEVEL_FIFA => 'FIFA Certified',
            self::LEVEL_INTERNATIONAL => 'International',
            self::LEVEL_NATIONAL => 'National',
            self::LEVEL_REGIONAL => 'Regional',
            self::LEVEL_LOCAL => 'Local',
            self::LEVEL_EMERGING => 'Emerging/Student',
        ];
    }

    /**
     * Get the tournament this referee belongs to
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get matches officiated by this referee
     */
    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'referee_id');
    }

    /**
     * Get matches as assistant referee 1
     */
    public function assistantMatches1(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'assistant_referee_1_id');
    }

    /**
     * Get matches as assistant referee 2
     */
    public function assistantMatches2(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'assistant_referee_2_id');
    }

    /**
     * Get all matches officiated (any role)
     */
    public function allMatches(): array
    {
        return array_merge(
            $this->matches()->pluck('id')->toArray(),
            $this->assistantMatches1()->pluck('id')->toArray(),
            $this->assistantMatches2()->pluck('id')->toArray()
        );
    }

    /**
     * Scope for active referees
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a specific tournament
     */
    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    /**
     * Scope for available referees on a given date/time
     */
    public function scopeAvailable($query, $tournament, $dateTime, $duration = 90)
    {
        $endTime = $dateTime->copy()->addMinutes($duration);

        return $query->active()
            ->where('tournament_id', $tournament->id)
            ->whereDoesntHave('matches', function ($q) use ($dateTime, $endTime) {
                $q->where(function ($subQ) use ($dateTime, $endTime) {
                    $subQ->where('kickoff_time', '<', $endTime)
                        ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
                });
            });
    }

    /**
     * Check if referee is available at given time
     */
    public function isAvailableAt($dateTime, $duration = 90): bool
    {
        $endTime = $dateTime->copy()->addMinutes($duration);

        // Check main referee matches
        if ($this->matches()
            ->where(function ($q) use ($dateTime, $endTime) {
                $q->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            })
            ->exists()) {
            return false;
        }

        // Check assistant referee assignments
        if ($this->assistantMatches1()
            ->where(function ($q) use ($dateTime, $endTime) {
                $q->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            })
            ->exists()) {
            return false;
        }

        if ($this->assistantMatches2()
            ->where(function ($q) use ($dateTime, $endTime) {
                $q->where('kickoff_time', '<', $endTime)
                    ->whereRaw('DATE_ADD(kickoff_time, INTERVAL COALESCE(match_format->>"$.duration", 90) MINUTE) > ?', [$dateTime]);
            })
            ->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Record match completion and update stats
     */
    public function recordMatchCompletion(float $performanceRating): void
    {
        $this->increment('matches_officiated');

        // Update average rating
        $newRating = (($this->rating * $this->matches_officiated) + $performanceRating) / ($this->matches_officiated + 1);
        $this->update(['rating' => round($newRating, 2)]);
    }

    /**
     * Get display name with certification
     */
    public function getDisplayName(): string
    {
        $cert = $this->certification_level ? ' (' . self::getCertificationLevels()[$this->certification_level] . ')' : '';
        return "{$this->name}{$cert}";
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return $this->is_active ? 'success' : 'secondary';
    }

    /**
     * Get rating stars display
     */
    public function getRatingStars(): string
    {
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return str_repeat('★', $fullStars) .
               ($halfStar ? '½' : '') .
               str_repeat('☆', $emptyStars);
    }

    /**
     * Get training sessions for this referee
     */
    public function trainingSessions(): HasMany
    {
        return $this->hasMany(RefereeTrainingSession::class);
    }

    /**
     * Get performance reviews for this referee
     */
    public function performanceReviews(): HasMany
    {
        return $this->hasMany(RefereePerformanceReview::class);
    }

    /**
     * Get upcoming training sessions
     */
    public function upcomingTrainingSessions(int $limit = 5)
    {
        return $this->trainingSessions()
            ->upcoming()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent performance reviews
     */
    public function recentPerformanceReviews(int $months = 6, int $limit = 5)
    {
        return $this->performanceReviews()
            ->recent($months)
            ->limit($limit)
            ->get();
    }

    /**
     * Get total training hours
     */
    public function getTotalTrainingHoursAttribute(): float
    {
        return $this->trainingSessions()
            ->completed()
            ->sum('hours_credited');
    }

    /**
     * Get average performance rating
     */
    public function getAveragePerformanceRatingAttribute(): ?float
    {
        return $this->performanceReviews()->avg('overall_rating');
    }

    /**
     * Get performance trend
     */
    public function getPerformanceTrendAttribute(): ?string
    {
        return RefereePerformanceReview::getRecentTrend($this->id);
    }

    /**
     * Check if referee needs training
     */
    public function needsTraining(int $requiredHoursPerYear = 20): bool
    {
        $hoursThisYear = $this->trainingSessions()
            ->completed()
            ->whereYear('session_date', now()->year)
            ->sum('hours_credited');

        return $hoursThisYear < $requiredHoursPerYear;
    }

    /**
     * Get next scheduled match
     */
    public function getNextMatch()
    {
        return $this->matches()
            ->where('match_date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->orderBy('kickoff_time')
            ->first();
    }

    /**
     * Get recent matches (last N)
     */
    public function getRecentMatches(int $limit = 5)
    {
        return $this->matches()
            ->where(function ($q) {
                $q->where('status', 'completed')
                    ->orWhere('match_date', '<', now()->toDateString());
            })
            ->orderBy('match_date', 'desc')
            ->orderBy('kickoff_time', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Scope for referees by certification level
     */
    public function scopeCertificationLevel($query, string $level)
    {
        return $query->where('certification_level', $level);
    }

    /**
     * Scope for referees with minimum rating
     */
    public function scopeMinRating($query, float $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope for referees with minimum matches
     */
    public function scopeMinMatches($query, int $minMatches)
    {
        return $query->where('matches_officiated', '>=', $minMatches);
    }

    /**
     * Get certification level label
     */
    public function getCertificationLevelLabelAttribute(): string
    {
        return self::getCertificationLevels()[$this->certification_level] ?? $this->certification_level;
    }

    /**
     * Check if referee is eligible for promotion
     */
    public function isEligibleForPromotion(): bool
    {
        // Basic eligibility criteria
        if ($this->matches_officiated < 20) return false;
        if ($this->rating < 7.0) return false;
        if ($this->needsTraining()) return false;

        return true;
    }

    /**
     * Get workload summary
     */
    public function getWorkloadSummaryAttribute(): array
    {
        $scheduledMatches = $this->matches()
            ->where('match_date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->count();

        return [
            'total_matches' => $this->matches_officiated,
            'scheduled_matches' => $scheduledMatches,
            'average_rating' => $this->average_performance_rating,
            'training_hours_year' => $this->trainingSessions()
                ->completed()
                ->whereYear('session_date', now()->year)
                ->sum('hours_credited'),
        ];
    }
}
