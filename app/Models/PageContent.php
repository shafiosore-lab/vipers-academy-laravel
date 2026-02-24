<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
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
     * Get content for a specific page and section
     */
    public static function getSection(string $page, string $section): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('page', $page)
            ->where('section', $section)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get a single content item by page, section and key
     */
    public static function getContent(string $page, string $section, string $key): ?self
    {
        return self::where('page', $page)
            ->where('section', $section)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get title for a section
     */
    public static function getTitle(string $page, string $section): string
    {
        $content = self::getContent($page, $section, 'title');
        return $content?->value ?? '';
    }

    /**
     * Get all journey timeline entries
     */
    public static function getJourneyEntries(): array
    {
        $entries = self::getSection('about', 'journey')
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
     */
    public static function getValuesEntries(): array
    {
        $entries = self::getSection('about', 'values')
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
}
