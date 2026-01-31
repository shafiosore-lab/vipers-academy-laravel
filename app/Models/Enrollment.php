<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'residence',
        'learning_option',
        'program_id',
    ];

    /**
     * Get the program that the enrollment is for.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
