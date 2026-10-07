<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RefereePerformanceReview Model
 *
 * Tracks performance reviews for referees including
 * post-match evaluations, periodic reviews, and annual assessments
 */
class RefereePerformanceReview extends Model
{
    protected $fillable = [
        'referee_id',
        'match_id',
        'review_date',
        'review_type',
        'overall_rating',
        'decision_accuracy',
        'game_management',
        'fitness_rating',
        'communication',
        'strengths',
        'areas_for_improvement',
        'comments',
        'reviewed_by',
    ];

    protected $casts = [
        'review_date' => 'date',
        'overall_rating' => 'decimal:2',
        'decision_accuracy' => 'decimal:2',
        'game_management' => 'decimal:2',
        'fitness_rating' => 'decimal:2',
        'communication' => 'decimal:2',
    ];

    // Review type constants
    const TYPE_POST_MATCH = 'post_match';
    const TYPE_PERIODIC = 'periodic';
    const TYPE_ANNUAL = 'annual';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_DEMOTION = 'demotion';
    const TYPE_TRAINING = 'training';

    /**
     * Get review types
     */
    public static function getReviewTypes(): array
    {
        return [
            self::TYPE_POST_MATCH => 'Post-Match Review',
            self::TYPE_PERIODIC => 'Periodic Review',
            self::TYPE_ANNUAL => 'Annual Assessment',
            self::TYPE_PROMOTION => 'Promotion Review',
            self::TYPE_DEMOTION => 'Demotion Review',
            self::TYPE_TRAINING => 'Training Assessment',
        ];
    }

    /**
     * Get the referee for this review
     */
    public function referee(): BelongsTo
    {
        return $this->belongsTo(TournamentReferee::class);
    }

    /**
     * Get the match (if applicable)
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class, 'match_id');
    }

    /**
     * Get the user who conducted the review
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope for post-match reviews
     */
    public function scopePostMatch($query)
    {
        return $query->where('review_type', self::TYPE_POST_MATCH);
    }

    /**
     * Scope for periodic reviews
     */
    public function scopePeriodic($query)
    {
        return $query->where('review_type', self::TYPE_PERIODIC);
    }

    /**
     * Scope for annual reviews
     */
    public function scopeAnnual($query)
    {
        return $query->where('review_type', self::TYPE_ANNUAL);
    }

    /**
     * Scope for a specific referee
     */
    public function scopeForReferee($query, int $refereeId)
    {
        return $query->where('referee_id', $refereeId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('review_date', [$startDate, $endDate]);
    }

    /**
     * Scope for recent reviews
     */
    public function scopeRecent($query, int $months = 6)
    {
        return $query->where('review_date', '>=', now()->subMonths($months))
            ->orderBy('review_date', 'desc');
    }

    /**
     * Get average rating across all metrics
     */
    public function getAverageMetricRatingAttribute(): ?float
    {
        $ratings = array_filter([
            $this->decision_accuracy,
            $this->game_management,
            $this->fitness_rating,
            $this->communication,
        ]);

        if (empty($ratings)) {
            return null;
        }

        return round(array_sum($ratings) / count($ratings), 2);
    }

    /**
     * Get review type label
     */
    public function getReviewTypeLabelAttribute(): string
    {
        return self::getReviewTypes()[$this->review_type] ?? $this->review_type;
    }

    /**
     * Get overall rating label
     */
    public function getOverallRatingLabelAttribute(): string
    {
        if (!$this->overall_rating) {
            return 'N/A';
        }

        if ($this->overall_rating >= 9) return 'Excellent';
        if ($this->overall_rating >= 8) return 'Very Good';
        if ($this->overall_rating >= 7) return 'Good';
        if ($this->overall_rating >= 6) return 'Satisfactory';
        if ($this->overall_rating >= 5) return 'Acceptable';
        if ($this->overall_rating >= 4) return 'Needs Improvement';
        return 'Poor';
    }

    /**
     * Get rating color class
     */
    public function getRatingColorClassAttribute(): string
    {
        if (!$this->overall_rating) {
            return 'text-gray-500';
        }

        if ($this->overall_rating >= 8) return 'text-green-600';
        if ($this->overall_rating >= 6) return 'text-blue-600';
        if ($this->overall_rating >= 4) return 'text-yellow-600';
        return 'text-red-600';
    }

    /**
     * Calculate trend from previous reviews
     */
    public function getTrendFromPreviousAttribute(): ?string
    {
        $previousReviews = self::where('referee_id', $this->referee_id)
            ->where('review_date', '<', $this->review_date)
            ->orderBy('review_date', 'desc')
            ->limit(3)
            ->get();

        if ($previousReviews->isEmpty()) {
            return null;
        }

        $previousAvg = $previousReviews->avg('overall_rating');

        if (!$previousAvg || !$this->overall_rating) {
            return null;
        }

        $diff = $this->overall_rating - $previousAvg;

        if ($diff > 0.5) return 'improving';
        if ($diff < -0.5) return 'declining';
        return 'stable';
    }

    /**
     * Get summary of performance
     */
    public function getPerformanceSummaryAttribute(): string
    {
        $strengths = $this->strengths ? explode(',', $this->strengths) : [];
        $improvements = $this->areas_for_improvement ? explode(',', $this->areas_for_improvement) : [];

        $summary = "Overall Rating: " . $this->overall_rating_label . "\n";

        if (!empty($strengths)) {
            $summary .= "Strengths: " . implode(', ', array_map('trim', $strengths)) . "\n";
        }

        if (!empty($improvements)) {
            $summary .= "Areas for Improvement: " . implode(', ', array_map('trim', $improvements));
        }

        return $summary;
    }

    /**
     * Static: Get referee performance history
     */
    public static function getPerformanceHistory(int $refereeId, int $limit = 10)
    {
        return self::where('referee_id', $refereeId)
            ->orderBy('review_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Static: Get average rating for a referee
     */
    public static function getAverageRating(int $refereeId): ?float
    {
        return self::where('referee_id', $refereeId)
            ->avg('overall_rating');
    }

    /**
     * Static: Get recent trend for a referee
     */
    public static function getRecentTrend(int $refereeId, int $months = 3): ?string
    {
        $reviews = self::where('referee_id', $refereeId)
            ->where('review_date', '>=', now()->subMonths($months))
            ->orderBy('review_date')
            ->get();

        if ($reviews->count() < 2) {
            return null;
        }

        $first = $reviews->first()->overall_rating;
        $last = $reviews->last()->overall_rating;
        $diff = $last - $first;

        if ($diff > 0.3) return 'improving';
        if ($diff < -0.3) return 'declining';
        return 'stable';
    }
}
