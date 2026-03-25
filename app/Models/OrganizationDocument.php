<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'content',
        'document_type',
        'version',
        'status',
        'is_template',
        'category',
        'file_path',
        'file_size',
        'mime_type',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
        'status' => 'string',
        'is_template' => 'boolean',
        'file_size' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the organization that owns the document.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user who created the document.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved the document.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the approval workflow for this document.
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class);
    }

    /**
     * Scope a query to only include active documents.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include templates.
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope a query to only include documents for a specific organization.
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Get available document types.
     */
    public static function getDocumentTypes()
    {
        return [
            'contract' => 'Contract',
            'policy' => 'Policy',
            'procedure' => 'Procedure',
            'form' => 'Form',
            'template' => 'Template',
            'report' => 'Report',
            'letter' => 'Letter',
            'agreement' => 'Agreement',
        ];
    }

    /**
     * Get available document categories.
     */
    public static function getCategories()
    {
        return [
            'hr' => 'Human Resources',
            'finance' => 'Finance',
            'operations' => 'Operations',
            'legal' => 'Legal',
            'compliance' => 'Compliance',
            'marketing' => 'Marketing',
            'training' => 'Training',
            'administrative' => 'Administrative',
        ];
    }

    /**
     * Get available document statuses.
     */
    public static function getStatuses()
    {
        return [
            'draft' => 'Draft',
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'archived' => 'Archived',
            'expired' => 'Expired',
        ];
    }
}
