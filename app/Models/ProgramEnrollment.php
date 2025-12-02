<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramEnrollment extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'status',
        'enrollment_date',
        'start_date',
        'end_date',
        'fee_amount',
        'payment_status',
        'notes',
        'enrollment_data',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'fee_amount' => 'decimal:2',
        'enrollment_data' => 'array',
    ];

    /**
     * Get the user that owns the enrollment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the program that the enrollment is for.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Check if the enrollment is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if payment is completed.
     */
    public function isPaymentCompleted(): bool
    {
        return $this->payment_status === 'paid';
    }
}
