<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySettings extends Model
{
    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_website',
        'logo_path',
        'pdf_footer_enabled',
        'pdf_footer_text',
        'is_active',
    ];

    protected $casts = [
        'pdf_footer_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active company settings
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get company logo URL
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path && file_exists(public_path($this->logo_path))) {
            return asset($this->logo_path);
        }
        return asset('assets/img/logo.png'); // Default logo
    }

    /**
     * Get formatted footer text
     */
    public function getFormattedFooterAttribute(): string
    {
        $parts = [];

        if ($this->company_website) {
            $parts[] = 'Website: ' . $this->company_website;
        }
        if ($this->company_email) {
            $parts[] = 'Email: ' . $this->company_email;
        }
        if ($this->company_phone) {
            $parts[] = 'Phone: ' . $this->company_phone;
        }

        return implode(' | ', $parts);
    }

    /**
     * Validate email against company email list
     */
    public static function isValidCompanyEmail(string $email): bool
    {
        $company = self::getActive();
        if (!$company) {
            return false;
        }

        // Check if email matches company email
        return strtolower($email) === strtolower($company->company_email);
    }

    /**
     * Check if email is in the list of registered company emails
     */
    public static function isRegisteredEmail(string $email): bool
    {
        return self::whereRaw('LOWER(company_email) = ?', [strtolower($email)])->exists();
    }
}
