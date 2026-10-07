<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'letterhead_id',
        'created_by',
        'title',
        'content',
        'status',
        'page_count',
        'views',
    ];

    protected $casts = [
        'views' => 'integer',
        'page_count' => 'integer',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    const MAX_PAGES = 20;

    /**
     * Get the organization that owns this document.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the letterhead used by this document.
     */
    public function letterhead(): BelongsTo
    {
        return $this->belongsTo(OrganizationLetterhead::class, 'letterhead_id');
    }

    /**
     * Get the user who created this document.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get available statuses.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    /**
     * Scope to get draft documents.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope to get published documents.
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Increment view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Check if document is within page limit.
     */
    public function isWithinPageLimit(int $pages = null): bool
    {
        $pages = $pages ?? $this->page_count;
        return $pages <= self::MAX_PAGES;
    }

    /**
     * Get excerpt of content (strip HTML tags).
     */
    public function getExcerptAttribute(): string
    {
        if (!$this->content) {
            return '';
        }

        $text = strip_tags($this->content);
        $text = html_entity_decode($text);

        return substr($text, 0, 150) . (strlen($text) > 150 ? '...' : '');
    }
}
