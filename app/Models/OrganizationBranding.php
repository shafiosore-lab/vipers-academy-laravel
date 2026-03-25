<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationBranding extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'font_family',
        'logo_path',
        'favicon_path',
        'header_logo_path',
        'footer_logo_path',
        'letterhead_template',
        'email_template',
        'document_template',
        'social_media_links',
        'brand_guidelines',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'social_media_links' => 'array',
        'brand_guidelines' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the organization that owns the branding.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope a query to only include active branding.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include branding for a specific organization.
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Get default color scheme.
     */
    public static function getDefaultColors()
    {
        return [
            'primary' => '#007bff',
            'secondary' => '#6c757d',
            'accent' => '#28a745',
        ];
    }

    /**
     * Get available font families.
     */
    public static function getFontFamilies()
    {
        return [
            'Arial, sans-serif' => 'Arial',
            'Helvetica, sans-serif' => 'Helvetica',
            'Times New Roman, serif' => 'Times New Roman',
            'Georgia, serif' => 'Georgia',
            'Courier New, monospace' => 'Courier New',
            'Verdana, sans-serif' => 'Verdana',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS',
            'Tahoma, sans-serif' => 'Tahoma',
        ];
    }

    /**
     * Get default letterhead template.
     */
    public static function getDefaultLetterheadTemplate()
    {
        return [
            'header' => [
                'show_logo' => true,
                'show_address' => true,
                'show_contact' => true,
                'show_date' => true,
            ],
            'footer' => [
                'show_logo' => false,
                'show_address' => true,
                'show_contact' => true,
                'show_page_number' => true,
                'show_disclaimer' => true,
            ],
            'content' => [
                'margin_top' => '150px',
                'margin_bottom' => '100px',
                'margin_left' => '50px',
                'margin_right' => '50px',
            ],
        ];
    }

    /**
     * Get default email template.
     */
    public static function getDefaultEmailTemplate()
    {
        return [
            'header' => [
                'show_logo' => true,
                'background_color' => '#f8f9fa',
                'text_color' => '#333333',
            ],
            'footer' => [
                'show_contact' => true,
                'show_social_media' => true,
                'background_color' => '#343a40',
                'text_color' => '#ffffff',
            ],
            'content' => [
                'font_family' => 'Arial, sans-serif',
                'text_color' => '#333333',
                'link_color' => '#007bff',
            ],
        ];
    }
}
