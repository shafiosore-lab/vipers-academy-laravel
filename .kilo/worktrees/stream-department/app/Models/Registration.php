<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'guardian_name',
        'contact',
        'program_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
