<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
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
     * Get the player that owns this document.
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
