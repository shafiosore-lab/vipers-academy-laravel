<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Partner;
use App\Models\Staff;
use App\Models\Approval;
use App\Models\LoginActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

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
        'status', // 'active', 'pending', 'suspended'
        'profile_photo',
        'approved_at',
        'approved_by_user_id',
        'approval_notes',
        'partner_id', // for staff accounts - reference to partner who created them
        'last_login',
        'organization_id',
        'last_login_at',
        // Trial period fields
        'is_on_trial',
        'trial_ends_at',
        'trial_type',
        'trial_auto_activated',
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
            'is_on_trial' => 'boolean',
            'trial_ends_at' => 'datetime',
            'trial_auto_activated' => 'boolean',
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
     * Check if user is a super admin (platform owner).
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user is an organization admin.
     */
    public function isOrgAdmin(): bool
    {
        return $this->hasRole('org-admin');
    }

    /**
     * Check if user is a staff member.
     */
    public function isStaff()
    {
        return $this->user_type === 'staff';
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
        // First try to find by slug (primary method)
        if ($this->roles()->where('slug', $role)->exists()) {
            return true;
        }

        // Fallback: also check by name (case-insensitive) for backwards compatibility
        // Convert role to title case for name matching (e.g., 'super-admin' -> 'Super Admin')
        $roleName = ucwords(str_replace('-', ' ', $role));
        return $this->roles()->whereRaw('LOWER(name) = ?', [strtolower($roleName)])->exists();
    }

    /**
     * Check if user has any of the specified roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        // First try to find by slug
        if ($this->roles()->whereIn('slug', $roles)->exists()) {
            return true;
        }

        // Fallback: also check by name
        $lowerRoles = array_map('strtolower', $roles);
        return $this->roles()->whereIn('name', $lowerRoles)->exists();
    }

    /**
     * Check if user has a specific permission.
     * Supports wildcard matching (e.g., 'players.*' matches any players permission).
     */
    public function hasPermission(string $permission): bool
    {
        // Check exact match first
        if ($this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission);
        })->exists()) {
            return true;
        }

        // Check for wildcard permissions (e.g., 'players.*')
        if (str_contains($permission, '.*')) {
            $category = str_replace('.*', '', $permission);
            return $this->roles()->whereHas('permissions', function ($query) use ($category) {
                $query->where('slug', 'like', $category . '.%');
            })->exists();
        }

        return false;
    }

    /**
     * Check if user has any of the specified permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
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
     * Get the partners associated with this user (for staff accounts).
     */
    public function partners()
    {
        return $this->hasMany(Partner::class);
    }

    /**
     * Get the documents associated with this user.
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    /**
     * Get the payments associated with this user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the organization this user belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
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

    // =============================================
    // Trial Period Methods
    // =============================================

    /**
     * Check if user is on a trial period.
     */
    public function isOnTrial(): bool
    {
        return $this->is_on_trial === true && \Carbon\Carbon::now()->lt($this->trial_ends_at);
    }

    /**
     * Check if user's trial has expired.
     */
    public function isTrialExpired(): bool
    {
        if (!$this->is_on_trial) {
            return false;
        }
        return $this->trial_ends_at && \Carbon\Carbon::now()->gte($this->trial_ends_at);
    }

    /**
     * Get remaining trial days.
     */
    public function getRemainingTrialDays(): int
    {
        if (!$this->is_on_trial || !$this->trial_ends_at) {
            return 0;
        }
        return max(0, \Carbon\Carbon::now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Check if user has trial access for organization dashboard.
     */
    public function hasOrganizationTrialAccess(): bool
    {
        return $this->isOnTrial() && $this->trial_type === 'organization';
    }

    /**
     * Check if user has trial access for coach dashboard.
     */
    public function hasCoachTrialAccess(): bool
    {
        return $this->isOnTrial() && $this->trial_type === 'coach';
    }

    /**
     * Check if user has trial access for team manager dashboard.
     */
    public function hasTeamManagerTrialAccess(): bool
    {
        return $this->isOnTrial() && $this->trial_type === 'team_manager';
    }

    /**
     * Check if user has trial access for partner dashboard.
     */
    public function hasPartnerTrialAccess(): bool
    {
        return $this->isOnTrial() && $this->trial_type === 'partner';
    }

    /**
     * Check if user has trial access for player dashboard.
     */
    public function hasPlayerTrialAccess(): bool
    {
        return $this->isOnTrial() && $this->trial_type === 'player';
    }

    /**
     * Activate trial period for the user.
     */
    public function activateTrial(string $trialType, int $days = 10): void
    {
        $this->update([
            'is_on_trial' => true,
            'trial_ends_at' => now()->addDays($days),
            'trial_type' => $trialType,
            'trial_auto_activated' => true,
            'approval_status' => 'approved', // Auto-approve trial users
            'approved_at' => now(),
            'status' => 'active',
        ]);
    }

    /**
     * End trial period.
     */
    public function endTrial(): void
    {
        $this->update([
            'is_on_trial' => false,
            'trial_ends_at' => null,
            'trial_auto_activated' => false,
        ]);
    }

    /**
     * Get trial status message for display.
     */
    public function getTrialStatusMessage(): ?string
    {
        if (!$this->is_on_trial) {
            return null;
        }

        if ($this->isTrialExpired()) {
            return 'Your free trial has expired. Please upgrade to continue using the platform.';
        }

        $daysRemaining = $this->getRemainingTrialDays();
        $typeLabel = match($this->trial_type) {
            'organization' => 'Organization Dashboard',
            'coach' => 'Coach Dashboard',
            'team_manager' => 'Team Manager Dashboard',
            'partner' => 'Partner Dashboard',
            'player' => 'Player Dashboard',
            default => 'Full Access',
        };

        if ($daysRemaining === 1) {
            return "Your free trial ends tomorrow! You have {$typeLabel} access for 1 more day.";
        }

        return "You have {$daysRemaining} days remaining in your free trial of the {$typeLabel}.";
    }
}
