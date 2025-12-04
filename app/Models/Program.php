<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'age_group',
        'category',
        'fees',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'fees' => 'decimal:2',
    ];

    /**
     * Get the players enrolled in this program
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Get the program enrollments for this program
     */
    public function enrollments()
    {
        return $this->hasMany(ProgramEnrollment::class);
    }
}
