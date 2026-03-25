<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'max_users',
        'max_players',
        'max_staff',
        'features',
        'is_active',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_users' => 'integer',
        'max_players' => 'integer',
        'max_staff' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Default features for each plan (legacy structure)
    const STARTER_FEATURES = [
        'players_management' => true,
        'teams' => true,
        'basic_reports' => true,
        'attendance_tracking' => true,
        'training_sessions' => true,
    ];

    const PROFESSIONAL_FEATURES = [
        'players_management' => true,
        'teams' => true,
        'basic_reports' => true,
        'advanced_reports' => true,
        'attendance_tracking' => true,
        'training_sessions' => true,
        'finance_module' => true,
        'parent_portal' => true,
        'player_portal' => true,
    ];

    const ENTERPRISE_FEATURES = [
        'players_management' => true,
        'teams' => true,
        'basic_reports' => true,
        'advanced_reports' => true,
        'custom_reports' => true,
        'attendance_tracking' => true,
        'training_sessions' => true,
        'finance_module' => true,
        'parent_portal' => true,
        'player_portal' => true,
        'api_access' => true,
        'custom_branding' => true,
        'priority_support' => true,
        'white_label' => true,
    ];

    // Module-based permissions structure
    // Each module has an array of permission slugs
    const MODULE_PERMISSIONS = [
        'attendance' => [
            'attendance.clock_in',
            'attendance.clock_out',
            'attendance.mark',
            'attendance.view_history',
        ],
        'communication' => [
            'communication.approve_announcements',
            'communication.send_bulk',
            'communication.send_team',
        ],
        'content' => [
            'content.create_news',
            'content.delete_news',
            'content.edit_news',
            'content.view_news',
        ],
        'documents' => [
            'documents.approve',
            'documents.upload',
            'documents.view',
        ],
        'finance' => [
            'finance.process_payments',
            'finance.view_reports',
            'finance.view_payments',
        ],
        'jobs' => [
            'jobs.create',
            'jobs.delete',
            'jobs.edit',
            'jobs.view',
        ],
        'matches' => [
            'matches.create',
            'matches.delete',
            'matches.edit',
            'matches.view',
        ],
        'orders' => [
            'orders.manage_status',
            'orders.process',
            'orders.view',
        ],
        'partners' => [
            'partners.approve',
            'partners.create',
            'partners.create_staff',
            'partners.delete',
            'partners.edit',
            'partners.manage_roles',
            'partners.view_analytics',
            'partners.view',
        ],
        'players' => [
            'players.approve',
            'players.create',
            'players.delete',
            'players.edit',
            'players.update_profile',
            'players.view_portal',
            'players.view',
            'players.view_training',
        ],
        'programs' => [
            'programs.create',
            'programs.delete',
            'programs.edit',
            'programs.view',
        ],
        'reports' => [
            'reports.export',
            'reports.generate',
        ],
        'sessions' => [
            'sessions.add_notes',
            'sessions.end',
            'sessions.start',
        ],
        'statistics' => [
            'statistics.create',
            'statistics.delete',
            'statistics.edit',
            'statistics.view',
        ],
        'system' => [
            'system.manage_roles',
            'system.manage_settings',
            'system.view_logs',
        ],
        'teams' => [
            'teams.assign_players',
            'teams.create',
            'teams.edit',
        ],
        'users' => [
            'users.approve',
            'users.create',
            'users.delete',
            'users.edit',
            'users.view',
        ],
    ];

    // Starter plan module access (basic modules only)
    const STARTER_MODULES = [
        'players' => ['players.view', 'players.create'],
        'programs' => ['programs.view'],
        'teams' => ['teams.view', 'teams.create'],
        'attendance' => ['attendance.view_history'],
        'sessions' => ['sessions.start', 'sessions.end'],
    ];

    // Professional plan module access (most modules)
    const PROFESSIONAL_MODULES = [
        'players' => true,
        'programs' => true,
        'teams' => true,
        'attendance' => true,
        'sessions' => true,
        'statistics' => true,
        'matches' => true,
        'content' => true,
        'documents' => true,
        'jobs' => true,
        'orders' => true,
        'partners' => true,
        'reports' => true,
        'users' => true,
    ];

    // Enterprise plan module access (all modules)
    const ENTERPRISE_MODULES = [
        'players' => true,
        'programs' => true,
        'teams' => true,
        'attendance' => true,
        'sessions' => true,
        'statistics' => true,
        'matches' => true,
        'content' => true,
        'documents' => true,
        'finance' => true,
        'jobs' => true,
        'orders' => true,
        'partners' => true,
        'reports' => true,
        'system' => true,
        'users' => true,
        'communication' => true,
    ];

    // Relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'subscription_plan_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // Helper methods
    public function hasFeature(string $feature): bool
    {
        $features = $this->features ?? [];
        return isset($features[$feature]) && $features[$feature] === true;
    }

    public function getFormattedPrice(): string
    {
        return 'KES ' . number_format($this->price, 2);
    }

    public function getFeatureList(): array
    {
        return $this->features ?? [];
    }

    public function getActiveFeaturesCount(): int
    {
        return collect($this->features ?? [])->filter(fn($value) => $value === true)->count();
    }

    public function isUnlimited(string $type): bool
    {
        $value = match($type) {
            'users' => $this->max_users,
            'players' => $this->max_players,
            'staff' => $this->max_staff,
            default => null,
        };

        return $value === -1 || $value === null;
    }

    public function getLimit(string $type): string
    {
        return match($type) {
            'users' => $this->max_users == -1 ? 'Unlimited' : (string) $this->max_users,
            'players' => $this->max_players == -1 ? 'Unlimited' : (string) $this->max_players,
            'staff' => $this->max_staff == -1 ? 'Unlimited' : (string) $this->max_staff,
            default => '0',
        };
    }

    // Static methods for plan lookup
    public static function getBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->active()->first();
    }

    public static function getStarter(): ?self
    {
        return self::getBySlug('starter');
    }

    public static function getProfessional(): ?self
    {
        return self::getBySlug('professional');
    }

    public static function getEnterprise(): ?self
    {
        return self::getBySlug('enterprise');
    }

    // Permission management
    public function getPermissions(): array
    {
        $features = $this->features ?? [];
        return $features['permissions'] ?? [];
    }

    public function setPermissions(array $permissionIds): void
    {
        $features = $this->features ?? [];
        $features['permissions'] = $permissionIds;
        $this->features = $features;
    }

    public function hasPermission(string $permissionId): bool
    {
        return in_array($permissionId, $this->getPermissions());
    }

    public function assignPermission(string $permissionId): void
    {
        $permissions = $this->getPermissions();
        if (!in_array($permissionId, $permissions)) {
            $permissions[] = $permissionId;
            $this->setPermissions($permissions);
        }
    }

    public function removePermission(string $permissionId): void
    {
        $permissions = array_filter($this->getPermissions(), function($p) use ($permissionId) {
            return $p !== $permissionId;
        });
        $this->setPermissions(array_values($permissions));
    }

    // Module-based permission checking
    /**
     * Get the module access configuration for this plan
     */
    public function getModuleAccess(): array
    {
        $slug = $this->slug;

        return match($slug) {
            'starter' => self::STARTER_MODULES,
            'professional' => self::PROFESSIONAL_MODULES,
            'enterprise' => self::ENTERPRISE_MODULES,
            default => [],
        };
    }

    /**
     * Check if a module is accessible in this plan
     */
    public function hasModuleAccess(string $module): bool
    {
        $moduleAccess = $this->getModuleAccess();

        // Check if module exists in access config
        if (!isset($moduleAccess[$module])) {
            return false;
        }

        // If value is true, module is fully accessible
        if ($moduleAccess[$module] === true) {
            return true;
        }

        // If array, module has limited permissions
        return !empty($moduleAccess[$module]);
    }

    /**
     * Get allowed permissions for a specific module in this plan
     */
    public function getModulePermissions(string $module): array
    {
        $moduleAccess = $this->getModuleAccess();

        // Check if module is fully accessible (true = all permissions)
        if (isset($moduleAccess[$module]) && $moduleAccess[$module] === true) {
            return self::MODULE_PERMISSIONS[$module] ?? [];
        }

        // Check if module has limited access (array of allowed permissions)
        if (isset($moduleAccess[$module]) && is_array($moduleAccess[$module])) {
            return $moduleAccess[$module];
        }

        return [];
    }

    /**
     * Get all allowed permissions for this plan
     */
    public function getAllAllowedPermissions(): array
    {
        $moduleAccess = $this->getModuleAccess();
        $allowedPermissions = [];

        foreach ($moduleAccess as $module => $access) {
            if ($access === true) {
                // Get all permissions for this module
                $allowedPermissions = array_merge(
                    $allowedPermissions,
                    self::MODULE_PERMISSIONS[$module] ?? []
                );
            } elseif (is_array($access)) {
                // Get specific permissions allowed
                $allowedPermissions = array_merge($allowedPermissions, $access);
            }
        }

        return array_unique($allowedPermissions);
    }

    /**
     * Check if a specific permission is allowed in this plan
     */
    public function allowsPermission(string $permission): bool
    {
        // Extract module from permission (e.g., 'players.create' -> 'players')
        $parts = explode('.', $permission);
        $module = $parts[0] ?? null;

        if (!$module) {
            return false;
        }

        $moduleAccess = $this->getModuleAccess();

        // Check if module has full access
        if (isset($moduleAccess[$module]) && $moduleAccess[$module] === true) {
            return true;
        }

        // Check if specific permission is allowed
        if (isset($moduleAccess[$module]) && is_array($moduleAccess[$module])) {
            return in_array($permission, $moduleAccess[$module]);
        }

        return false;
    }

    /**
     * Get available modules for this plan
     */
    public function getAvailableModules(): array
    {
        return array_keys($this->getModuleAccess());
    }

    /**
     * Get all module permissions structured for display
     */
    public function getStructuredPermissions(): array
    {
        $result = [];

        foreach (self::MODULE_PERMISSIONS as $module => $permissions) {
            $allowedPermissions = $this->getModulePermissions($module);

            $result[$module] = [
                'name' => ucfirst($module),
                'has_full_access' => $this->hasModuleAccess($module),
                'permissions' => array_map(function($perm) use ($allowedPermissions) {
                    $parts = explode('.', $perm);
                    $action = $parts[1] ?? $perm;

                    return [
                        'slug' => $perm,
                        'name' => ucfirst(str_replace('_', ' ', $action)),
                        'allowed' => in_array($perm, $allowedPermissions),
                    ];
                }, $permissions),
            ];
        }

        return $result;
    }
}
