<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // 'admin', 'player', 'partner', 'visitor'
        'status', // 'active', 'pending', 'suspended'
        'player_id', // reference to players table for player users
        'partner_details', // JSON field for partner information
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'partner_details' => 'array',
        ];
    }

    /**
     * Get the player associated with this user.
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get the players managed by this partner organization.
     */
    public function managedPlayers()
    {
        return $this->hasMany(Player::class, 'partner_id');
    }

    /**
     * Get the visitor profile for this user.
     */
    public function visitorProfile()
    {
        return $this->hasOne(VisitorProfile::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if user is a player.
     */
    public function isPlayer()
    {
        return $this->user_type === 'player';
    }

    /**
     * Check if user is a partner.
     */
    public function isPartner()
    {
        return $this->user_type === 'partner';
    }

    /**
     * Check if user is a visitor.
     */
    public function isVisitor()
    {
        return $this->user_type === 'visitor';
    }

    /**
     * Check if user account is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if user account is pending approval.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
