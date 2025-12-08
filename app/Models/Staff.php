<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'role_id',
    ];

    /**
     * Get the user that owns this staff profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the partner that this staff member belongs to.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the role for this staff member.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
