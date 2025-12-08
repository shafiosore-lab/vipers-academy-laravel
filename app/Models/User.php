<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Partner;
use App\Models\Staff;
use App\Models\Approval;
use App\Models\LoginActivity;

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
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'user_type', // 'admin', 'partner', 'staff', 'player'
        'approval_status', // 'pending', 'approved', 'rejected'
        'profile_photo',
        'approved_at',
        'approved_by_user_id',
        'approval_notes',
        'partner_id', // for staff accounts - reference to partner who created them
        'last_login',
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
            'approved_at' => 'datetime',
            'last_login' => 'datetime',
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
     * Get the roles that belong to this user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the user who approved this account.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get the partner who created this staff account.
     */
    public function createdByPartner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get staff accounts created by this partner.
     */
    public function createdStaff()
    {
        return $this->hasMany(User::class, 'partner_id');
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
        return $this->approval_status === 'approved';
    }

    /**
     * Check if user account is pending approval.
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if user is approved.
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if user account is rejected.
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if user has any of the specified roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission);
        })->exists();
    }

    /**
     * Assign a role to this user.
     */
    public function assignRole(string|Role $role): void
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('slug', $role)->first()->id;
        $this->roles()->attach($roleId);
    }

    /**
     * Remove a role from this user.
     */
    public function removeRole(string|Role $role): void
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('slug', $role)->first()->id;
        $this->roles()->detach($roleId);
    }

    /**
     * Get all permissions for this user through their roles.
     */
    public function getAllPermissions()
    {
        return $this->roles->flatMap->permissions->unique('id');
    }

    /**
     * Approve this user account.
     */
    public function approve(User $approvedBy = null, string $notes = null): void
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by_user_id' => $approvedBy ? $approvedBy->id : null,
            'approval_notes' => $notes,
        ]);
    }

    /**
     * Get profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_photo && file_exists(public_path('storage/profile-images/' . $this->profile_photo))) {
            return asset('storage/profile-images/' . $this->profile_photo);
        }

        // Return default avatar based on user type
        return match($this->user_type) {
            'admin' => asset('assets/img/default-admin.png'),
            'partner' => asset('assets/img/default-partner.png'),
            'player' => $this->player && $this->player->website_image
                ? asset('assets/img/players/' . $this->player->website_image)
                : asset('assets/img/default-player.png'),
            default => asset('assets/img/default-user.png'),
        };
    }

    /**
     * Get the partner profile for this user.
     */
    public function partner()
    {
        return $this->hasOne(Partner::class);
    }

    /**
     * Get the staff profile for this user.
     */
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Get all approvals performed by this user.
     */
    public function approvalsGiven()
    {
        return $this->hasMany(Approval::class, 'approved_by');
    }

    /**
     * Get all approvals received by this user.
     */
    public function approvalsReceived()
    {
        return $this->hasMany(Approval::class, 'user_id');
    }

    /**
     * Get login activity for this user.
     */
    public function loginActivity()
    {
        return $this->hasMany(LoginActivity::class);
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Check if user can access partner features.
     */
    public function canAccessPartnerFeatures(): bool
    {
        return $this->isPartner() && $this->isApproved();
    }

    /**
     * Check if user can access player features.
     */
    public function canAccessPlayerFeatures(): bool
    {
        return $this->isPlayer() && $this->isApproved();
    }

    /**
     * Check if user can create staff accounts (for partners).
     */
    public function canCreateStaff(): bool
    {
        return $this->isPartner() && $this->isApproved();
    }
}
