<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationLetterhead extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'style',
        'header_alignment',
        'primary_color',
        'show_watermark',
        'show_page_numbers',
        'custom_footer_note',
        'signature_image',
        'signature_name',
        'signature_title',
        'is_default',
    ];

    protected $casts = [
        'show_watermark' => 'boolean',
        'show_page_numbers' => 'boolean',
        'is_default' => 'boolean',
    ];

    const STYLE_CLASSIC = 'classic';
    const STYLE_MODERN = 'modern';
    const STYLE_MINIMAL = 'minimal';

    const ALIGNMENT_LEFT = 'left';
    const ALIGNMENT_CENTER = 'center';

    /**
     * Get the organization that owns this letterhead.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get documents using this letterhead.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OrganizationDocument::class, 'letterhead_id');
    }

    /**
     * Get the logo URL for this organization.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->organization && $this->organization->logo) {
            return asset('storage/' . $this->organization->logo);
        }
        return null;
    }

    /**
     * Get the signature image URL.
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if ($this->signature_image) {
            return asset('storage/' . $this->signature_image);
        }
        return null;
    }

    /**
     * Get available styles.
     */
    public static function getStyles(): array
    {
        return [
            self::STYLE_CLASSIC => 'Classic',
            self::STYLE_MODERN => 'Modern',
            self::STYLE_MINIMAL => 'Minimal',
        ];
    }

    /**
     * Get available alignments.
     */
    public static function getAlignments(): array
    {
        return [
            self::ALIGNMENT_LEFT => 'Left',
            self::ALIGNMENT_CENTER => 'Center',
        ];
    }

    /**
     * Scope to get the default letterhead for an organization.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the footer text for PDF generation.
     */
    public function getFooterText(): string
    {
        $footer = 'Generated via Mumias Vipers Sports Management SaaS Platform';

        if ($this->custom_footer_note) {
            $footer .= ' | ' . $this->custom_footer_note;
        }

        return $footer;
    }

    /**
     * Check if the organization has a logo uploaded.
     */
    public function hasLogo(): bool
    {
        return !empty($this->organization && $this->organization->logo);
    }
}
