<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'document_id',
        'title',
        'description',
        'category',
        'subcategory',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'language',
        'version',
        'is_mandatory',
        'target_roles',
        'requires_signature',
        'expiry_days',
        'is_active',
        'metadata',
        'published_at',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'is_mandatory' => 'boolean',
        'requires_signature' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'published_at' => 'datetime',
        'expiry_days' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the user documents for this document
     */
    public function userDocuments(): HasMany
    {
        return $this->hasMany(UserDocument::class);
    }

    /**
     * Check if the document is accessible by a specific role
     */
    public function isAccessibleByRole(string $role): bool
    {
        return in_array($role, $this->target_roles ?? []);
    }

    /**
     * Check if the document is mandatory for a specific role
     */
    public function isMandatoryForRole(string $role): bool
    {
        return $this->is_mandatory && $this->isAccessibleByRole($role);
    }

    /**
     * Get the full URL for the document
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Check if the document file exists
     */
    public function fileExists(): bool
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Get the file size in human readable format
     */
    public function getHumanFileSizeAttribute(): string
    {
        if (!$this->file_size) return 'N/A';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < 4) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the category display name
     */
    public function getCategoryDisplayNameAttribute(): string
    {
        return match($this->category) {
            'codes_of_conduct' => 'Codes of Conduct',
            'safety_protection' => 'Safety & Protection',
            'academy_policies' => 'Academy Policies',
            'contracts_agreements' => 'Contracts & Agreements',
            'academy_information' => 'Academy Information',
            'administrative' => 'Administrative',
            default => ucfirst(str_replace('_', ' ', $this->category))
        };
    }

    /**
     * Get documents by category
     */
    public static function getByCategory(string $category)
    {
        return self::where('category', $category)->active()->get();
    }

    /**
     * Get documents accessible by a specific role
     */
    public static function getAccessibleByRole(string $role)
    {
        return self::whereJsonContains('target_roles', $role)->active()->get();
    }

    /**
     * Get mandatory documents for a role
     */
    public static function getMandatoryForRole(string $role)
    {
        return self::whereJsonContains('target_roles', $role)
                  ->where('is_mandatory', true)
                  ->active()
                  ->get();
    }

    /**
     * Scope for active documents only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for documents expiring soon (for notifications)
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_days', '<=', $days)->whereNotNull('expiry_days');
    }

    /**
     * Check if document requires renewal for a user
     */
    public function requiresRenewalForUser(UserDocument $userDocument): bool
    {
        if (!$this->expiry_days || !$userDocument->expires_at) {
            return false;
        }

        return $userDocument->expires_at->isPast() ||
               now()->diffInDays($userDocument->expires_at, false) <= 7; // Renewal warning 7 days before expiry
    }

    /**
     * Get view count from user documents
     */
    public function getViewCountAttribute(): int
    {
        return $this->userDocuments()->whereIn('status', ['viewed', 'downloaded', 'signed'])->count();
    }

    /**
     * Get download count
     */
    public function getDownloadCountAttribute(): int
    {
        return $this->userDocuments()->sum('download_count');
    }

    /**
     * Get signature count
     */
    public function getSignatureCountAttribute(): int
    {
        return $this->userDocuments()->where('status', 'signed')->count();
    }

    /**
     * Check if the document is a PDF
     */
    public function isPdf(): bool
    {
        return str_contains($this->mime_type, 'pdf');
    }

    /**
     * Check if the document is a Word document
     */
    public function isWordDocument(): bool
    {
        return str_contains($this->mime_type, 'word') ||
               str_contains($this->mime_type, 'msword') ||
               str_contains($this->mime_type, 'officedocument');
    }

    /**
     * Check if the document is viewable in browser
     */
    public function isViewable(): bool
    {
        $viewableTypes = [
            'application/pdf',
            'text/plain',
            'text/html',
            'image/jpeg',
            'image/png',
            'image/gif',
        ];

        return in_array($this->mime_type, $viewableTypes);
    }
}
