<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'author',
        'category',
        'tags',
        'published_at',
        'is_featured',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Get the route key name for Laravel model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order by latest.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Get the category display name.
     */
    public function getCategoryIconAttribute(): string
    {
        $icons = [
            'Academy News' => '🏆',
            'Match Reports' => '⚽',
            'Player Updates' => '👤',
            'Training Updates' => '🏃',
            'Events' => '📅',
            'Announcements' => '📢',
            'Transfer News' => '🔄',
            'General' => '📰',
        ];

        return $icons[$this->category] ?? '📰';
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->published_at->format('M d, Y');
    }

    /**
     * Get the excerpt with limit.
     */
    public function getExcerpt(int $limit = 150): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->excerpt ?? $this->content), $limit);
    }
}
