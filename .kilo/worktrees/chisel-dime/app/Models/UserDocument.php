<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_id',
        'document_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'status',
        'viewed_at',
        'signed_at',
        'downloaded_at',
        'uploaded_at',
        'expires_at',
        'download_count',
        'is_mandatory_for_user',
        'signature_path',
        'user_metadata',
        'notes',
        'review_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'signed_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'expires_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'is_mandatory_for_user' => 'boolean',
        'user_metadata' => 'array',
        'download_count' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the user that owns this document relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Check if the document is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if document requires renewal soon (7 days)
     */
    public function requiresRenewalSoon(): bool
    {
        if (!$this->expires_at) return false;

        return now()->diffInDays($this->expires_at, false) <= 7;
    }

    /**
     * Check if document has been signed
     */
    public function isSigned(): bool
    {
        return $this->status === 'signed';
    }

    /**
     * Check if document has been viewed
     */
    public function isViewed(): bool
    {
        return in_array($this->status, ['viewed', 'downloaded', 'signed']);
    }

    /**
     * Mark document as viewed
     */
    public function markAsViewed(): void
    {
        if (!$this->viewed_at) {
            $this->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }
    }

    /**
     * Mark document as downloaded
     */
    public function markAsDownloaded(): void
    {
        $this->increment('download_count');
        $this->update([
            'status' => 'downloaded',
            'downloaded_at' => now(),
        ]);
    }

    /**
     * Mark document as signed
     */
    public function markAsSigned(?string $signaturePath = null): void
    {
        $this->update([
            'status' => 'signed',
            'signed_at' => now(),
            'signature_path' => $signaturePath,
        ]);
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expires_at) return null;

        return now()->diffInDays($this->expires_at, false);
    }

    /**
     * Scope for expired documents
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')->where('expires_at', '<', now());
    }

    /**
     * Scope for expiring soon
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '>=', now())
                    ->where('expires_at', '<=', now()->addDays($days));
    }

    /**
     * Scope for mandatory documents
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory_for_user', true);
    }

    /**
     * Scope for documents by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get formatted expiration status
     */
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expires_at) return 'No expiry';

        $daysUntilExpiry = $this->getDaysUntilExpiry();

        if ($daysUntilExpiry < 0) {
            return 'Expired ' . abs($daysUntilExpiry) . ' days ago';
        } elseif ($daysUntilExpiry === 0) {
            return 'Expires today';
        } elseif ($daysUntilExpiry <= 7) {
            return 'Expires in ' . $daysUntilExpiry . ' days';
        } else {
            return 'Expires in ' . $daysUntilExpiry . ' days';
        }
    }
}
