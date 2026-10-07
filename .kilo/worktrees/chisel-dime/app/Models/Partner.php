<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_name',
        'documents_submitted',
        'is_verified',
        'verification_notes',
    ];

    protected $casts = [
        'documents_submitted' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user that owns this partner profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the documents for this partner.
     */
    public function documents()
    {
        return $this->hasMany(PartnerDocument::class);
    }

    /**
     * Get the staff members for this partner.
     */
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
