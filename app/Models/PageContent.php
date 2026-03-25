<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageContent extends Model
{
    protected $fillable = [
        'organization_id',
        'page',
        'section',
        'key',
        'value',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the organization that owns this page content
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope to get global (non-organization) content
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('organization_id');
    }

    /**
     * Scope to get content for a specific organization
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope to get active content only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get content for a specific page and section
     * Now supports organization-specific content with fallback to global
     */
    public static function getSection(string $page, string $section, ?int $organizationId = null): \Illuminate\Database\Eloquent\Collection
    {
        return self::getSectionWithFallback($page, $section, $organizationId);
    }

    /**
     * Get a single content item by page, section and key
     * Now supports organization-specific content with fallback to global
     */
    public static function getContent(string $page, string $section, string $key, ?int $organizationId = null): ?self
    {
        return self::getContentWithFallback($page, $section, $key, $organizationId);
    }

    /**
     * Get title for a section
     * Now supports organization-specific content with fallback to global
     */
    public static function getTitle(string $page, string $section, ?int $organizationId = null): string
    {
        $content = self::getContent($page, $section, 'title', $organizationId);
        return $content?->value ?? '';
    }

    /**
     * Get all journey timeline entries
     * Now supports organization-specific content with fallback to global
     */
    public static function getJourneyEntries(?int $organizationId = null): array
    {
        $entries = self::getSection('about', 'journey', $organizationId)
            ->filter(fn($item) => str_starts_with($item->key, 'year_'));

        $result = [];
        foreach ($entries as $entry) {
            $year = str_replace('year_', '', $entry->key);
            $result[$year] = $entry->value;
        }

        return $result;
    }

    /**
     * Get all core values entries
     * Now supports organization-specific content with fallback to global
     */
    public static function getValuesEntries(?int $organizationId = null): array
    {
        $entries = self::getSection('about', 'values', $organizationId)
            ->filter(fn($item) => !str_starts_with($item->key, 'title'));

        // Parse values to extract icon, title, description
        $result = [];
        $defaultIcons = ['discipline' => '🎯', 'respect' => '🤝', 'hard_work' => '💪', 'child_safety' => '🛡️'];

        foreach ($entries->values()->all() as $entry) {
            $valueData = json_decode($entry->value, true);

            // If JSON, use the parsed data
            if (is_array($valueData)) {
                $result[] = (object)[
                    'id' => $entry->id,
                    'key' => $entry->key,
                    'value' => $entry->value,
                    'type' => $entry->type,
                    'sort_order' => $entry->sort_order,
                    'icon' => $valueData['icon'] ?? $defaultIcons[$entry->key] ?? '⭐',
                    'title' => $valueData['title'] ?? ucfirst(str_replace('_', ' ', $entry->key)),
                    'description' => $valueData['description'] ?? $entry->value,
                ];
            } else {
                // Plain text - convert to object format
                $result[] = (object)[
                    'id' => $entry->id,
                    'key' => $entry->key,
                    'value' => $entry->value,
                    'type' => $entry->type,
                    'sort_order' => $entry->sort_order,
                    'icon' => $defaultIcons[$entry->key] ?? '⭐',
                    'title' => ucfirst(str_replace('_', ' ', $entry->key)),
                    'description' => $entry->value,
                ];
            }
        }

        return $result;
    }

    /**
     * Update content value
     */
    public static function updateContent(string $page, string $section, string $key, string $value): bool
    {
        $content = self::where('page', $page)
            ->where('section', $section)
            ->where('key', $key)
            ->first();

        if (!$content) {
            return false;
        }

        $content->value = $value;
        return $content->save();
    }

    /**
     * Get section with fallback to global content
     * Priority: Organization specific -> Global default
     */
    public static function getSectionWithFallback(string $page, string $section, ?int $organizationId = null): \Illuminate\Database\Eloquent\Collection
    {
        // First try to get organization-specific content
        if ($organizationId) {
            $orgContent = self::where('page', $page)
                ->where('section', $section)
                ->forOrganization($organizationId)
                ->active()
                ->orderBy('sort_order')
                ->get();

            if ($orgContent->isNotEmpty()) {
                return $orgContent;
            }
        }

        // Fallback to global content
        return self::where('page', $page)
            ->where('section', $section)
            ->global()
            ->active()
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get single content with fallback to global
     */
    public static function getContentWithFallback(string $page, string $section, string $key, ?int $organizationId = null): ?self
    {
        // First try organization-specific
        if ($organizationId) {
            $content = self::where('page', $page)
                ->where('section', $section)
                ->where('key', $key)
                ->forOrganization($organizationId)
                ->active()
                ->first();

            if ($content) {
                return $content;
            }
        }

        // Fallback to global
        return self::where('page', $page)
            ->where('section', $section)
            ->where('key', $key)
            ->global()
            ->active()
            ->first();
    }
}
