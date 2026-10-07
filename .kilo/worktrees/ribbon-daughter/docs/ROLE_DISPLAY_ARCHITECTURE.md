# Role Display Architecture

This document describes the refactored role display system that provides clean separation between UI layer and permission validation logic.

## Overview

The new architecture ensures that:
1. **Primary roles** are displayed by default based on hierarchy priority
2. **Secondary/elevated roles** are hidden until explicitly granted access
3. Permission validation happens in the service layer, not the UI
4. Dashboard routes are protected by middleware to prevent bypass attacks

## Architecture Components

### 1. Role Configuration (`config/roles.php`)

Centralized role priority and settings management. This allows easy configuration without modifying service code.

```php
// Example configuration
'priority' => [
    'super-admin' => 100,
    'org-admin' => 80,
    'head-coach' => 60,
    // ...
],

'elevated_roles' => [
    'super-admin',
    'org-admin',
    // ...
],
```

### 2. RoleDisplayService (`app/Services/RoleDisplayService.php`)

The central service that handles all role display logic with clean separation from the UI.

**Key Responsibilities:**
- Determining primary role based on hierarchy priority
- Identifying secondary roles
- Checking if elevated role indicators should be shown
- Filtering taskbar items based on permissions

**Key Methods:**

```php
// Get the primary role (highest priority)
public function getPrimaryRole(User $user): ?Role

// Get all secondary roles
public function getSecondaryRoles(User $user): Collection

// Check if user should see elevated role indicators
public function shouldShowElevatedIndicators(User $user): bool

// Check if user can switch between dashboards
public function canSwitchDashboard(User $user): bool

// Get complete role display configuration
public function getRoleDisplayConfig(User $user): array
```

**Role Priority Hierarchy:**
```
super-admin (100) > org-admin (80) > head-coach (60) > coach (50) 
> assistant-coach (45) > team-manager (40) > finance-admin (35) 
> finance-officer (30) > operations-admin (35) > media-officer (25) 
> safeguarding-officer (25) > partner (20) > parent (10) > player (5) > trial (1)
```

### 2. DashboardRouteService (`app/Services/DashboardRouteService.php`)

Handles dashboard routing and taskbar configuration using RoleDisplayService.

**Key Features:**
- Delegates permission filtering to RoleDisplayService
- Provides taskbar configuration based on primary role
- Supports both primary and secondary role-based navigation

### 3. UserTypeService (`app/Services/UserTypeService.php`)

Extended with new methods for user type handling.

**New Methods:**
```php
// Get dashboard route by user type directly
public function getDashboardRouteByType(string $userType): string
```

### 4. Dashboard Base Layout (`resources/views/layouts/dashboard-base.blade.php`)

Updated to use permission-checked role display.

**Key Features:**
- Shows only primary role badge by default
- Role switcher only appears when user has elevated access
- Clean separation of UI and permission logic

## How It Works

### Primary Role Display Flow

1. User logs in
2. RoleDisplayService determines primary role based on hierarchy
3. DashboardRouteService generates taskbar based on primary role
4. View renders only primary role information

### Elevated Role Access Flow

1. User has multiple roles
2. User needs explicit permission (`view_elevated_roles`) to see secondary roles
3. When granted, role switcher appears in the UI
4. User can switch between dashboards

### Permission Check Flow

1. Taskbar items are defined with permission requirements
2. RoleDisplayService filters items based on user's permissions
3. Only items the user has permission to access are rendered
4. No permission logic in the view itself

## Usage Examples

### Getting Role Display Configuration

```php
$roleDisplayService = new RoleDisplayService();
$config = $roleDisplayService->getRoleDisplayConfig($user);

// Returns:
// [
//     'primary_role' => 'coach',
//     'primary_role_display' => 'Coach',
//     'show_elevated' => false,
//     'can_switch' => false,
//     'switchable_dashboards' => [],
//     'visible_roles' => ['coach'],
//     'is_elevated_user' => false,
// ]
```

### Checking Taskbar Access

```php
$canAccess = $roleDisplayService->canAccessTaskbarItem($user, 'manage_players');
```

### Filtering Taskbar Items

```php
$filteredItems = $roleDisplayService->filterTaskbarItemsByPermission($user, $items);
```

## Configuration

### Elevated Roles

Roles considered elevated (require explicit permission to display):
- super-admin
- org-admin
- head-coach
- team-manager
- finance-admin
- operations-admin

### Enabling Elevated Role Display

To allow a user to see secondary roles in the UI:

```php
// Option 1: Set user metadata
$user->update(['show_elevated_roles' => true]);

// Option 2: Assign permission
$user->givePermissionTo('view_elevated_roles');
```

## Migration Notes

The refactoring maintains backward compatibility:
- Existing dashboard routes still work
- Permission checks remain the same
- Super admin always sees everything
- Multi-role users can still access all their dashboards via direct routes

## Security: Route Protection Middleware

### EnsureDashboardAccess Middleware

The `EnsureDashboardAccess` middleware prevents users from bypassing UI restrictions by directly accessing dashboard URLs.

**Location:** `app/Http/Middleware/EnsureDashboardAccess.php`

**Usage in Routes:**

```php
// Protect super-admin routes
Route::middleware(['auth', 'EnsureDashboardAccess:super-admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminController::class, 'index']);
});

// Protect org-admin routes
Route::middleware(['auth', 'EnsureDashboardAccess:org-admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Protect staff routes
Route::middleware(['auth', 'EnsureDashboardAccess:staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index']);
});
```

**Features:**
- Validates user has the required role
- Prevents cross-organization access
- Logs unauthorized access attempts
- Redirects to appropriate dashboard with error message

## Testing

When testing the new architecture:

1. **Single role users** - Should see only their primary role
2. **Multi-role users without elevated access** - Should see only primary role, no switcher
3. **Multi-role users with elevated access** - Should see role switcher
4. **Super admins** - Should see everything regardless of settings
5. **Direct URL access** - Should be blocked by middleware if user lacks permissions
