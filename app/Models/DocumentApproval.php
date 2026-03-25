<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentApproval extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_document_id',
        'approver_id',
        'status',
        'comments',
        'approved_at',
        'required_approval_level',
        'approval_sequence',
        'is_final_approval',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'required_approval_level' => 'integer',
        'approval_sequence' => 'integer',
        'is_final_approval' => 'boolean',
    ];

    /**
     * Get the document that this approval belongs to.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(OrganizationDocument::class);
    }

    /**
     * Get the user who approved the document.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Scope a query to only include pending approvals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved approvals.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected approvals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get available approval statuses.
     */
    public static function getStatuses()
    {
        return [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get available approval levels.
     */
    public static function getApprovalLevels()
    {
        return [
            1 => 'Level 1 - Team Lead',
            2 => 'Level 2 - Department Manager',
            3 => 'Level 3 - Director',
            4 => 'Level 4 - VP',
            5 => 'Level 5 - CEO',
        ];
    }

    /**
     * Check if this is the final approval needed.
     */
    public function isFinalApprovalRequired(): bool
    {
        return $this->is_final_approval;
    }

    /**
     * Get the approval status label.
     */
    public function getStatusLabel(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the approval level label.
     */
    public function getLevelLabel(): string
    {
        $levels = self::getApprovalLevels();
        return $levels[$this->required_approval_level] ?? "Level {$this->required_approval_level}";
    }
}
