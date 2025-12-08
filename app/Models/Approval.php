<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'action',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who was approved/rejected.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who performed the approval/rejection.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
