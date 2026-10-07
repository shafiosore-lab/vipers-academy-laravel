<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'document_type',
        'file_path',
        'status',
        'admin_comment',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the partner that owns this document.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
