# System Hierarchy & Authorization Documentation

## Overview

This document outlines the role hierarchy, authorization flow, and middleware architecture for the multi-dashboard platform.

---

## Role Hierarchy

The system enforces the following authority flow:

```
🥇 SUPER ADMIN (Level 100)
   └─ Full system access
   └─ All organizations
   └─ Subscription management
   └─ Feature allocation
   └─ Can create/suspend/delete organizations
   
🥈 ORGANIZATION ADMIN (Level 50)
   └─ Full control within their organization
   └─ Cannot access other organizations
   └─ Can manage internal users
   └─ Cannot override Super Admin
   
🥉 MANAGERS & STAFF (Level 0-15)
   └─ Access limited to assigned modules
   └─ Cannot modify system configurations
   └─ Cannot modify organization ownership
   └─ Examples: head-coach, coach, team-manager, finance-officer

👥 STANDARD USERS (Level 1-2)
   └─ Only access assigned dashboard
   └─ Role-based restricted permissions
   └─ Examples: player, parent
```

### Role Priority Mapping

| Role Slug | Level | Dashboard | Module |
|-----------|-------|-----------|--------|
| super-admin | 100 | admin.dashboard | platform_administration |
| org-admin | 50 | organization.dashboard | organization_administration |
| operations-admin | 15 | admin.dashboard | admin_operations |
| finance-admin | 14 | admin.dashboard | admin_operations |
| coaching-admin | 13 | admin.dashboard | admin_operations |
| scouting-admin | 12 | admin.dashboard | admin_operations |
| marketing-admin | 11 | admin.dashboard | admin_operations |
| admin-operations | 10 | admin.dashboard | admin_operations |
| head-coach | 9 | coach.dashboard | coaching |
| finance-officer | 8 | finance.dashboard | finance |
| safeguarding-officer | 7 | welfare.dashboard | welfare |
| media-officer | 6 | media.dashboard | media |
| team-manager | 5 | manager.dashboard | management |
| coach | 4 | coach.dashboard | coaching |
| assistant-coach | 3 | coach.dashboard | coaching |
| parent | 2 | parent.dashboard | family |
| player | 1 | player.portal.dashboard | player |
| staff-base | 0 | coach.dashboard | base |

---

## Middleware Flow

### Request Flow Diagram

```
Request
   │
   ▼
┌─────────────────────┐
│ Authentication      │ ──► Checks: auth.session
└─────────────────────┘
   │
   ▼
┌─────────────────────┐
│ TenantScope         │ ──► Sets organization_id (skips super-admin)
└─────────────────────┘
   │
   ▼
┌─────────────────────┐
│ RoleMiddleware      │ ──► Validates role + permission
└─────────────────────┘
   │
   ▼
┌─────────────────────┐
│ CheckSuperAdmin     │ ──► Validates super-admin role (if route requires)
└─────────────────────┘
   │
   ▼
┌─────────────────────┐
│ CheckSubscription   │ ──► Validates subscription features
└─────────────────────┘
   │
   ▼
Controller
```

### Middleware Summary

| Middleware | Purpose | Bypasses |
|------------|---------|----------|
| `CheckSuperAdmin` | Validates super-admin role | None |
| `AdminMiddleware` | Validates admin/staff roles | super-admin (handled separately) |
| `RoleMiddleware` | Generic role + permission checking | None |
| `TenantScope` | Scopes data to organization | super-admin |
| `CheckSubscriptionAccess` | Validates subscription features | super-admin |
| `CheckFeature` | Validates specific feature access | super-admin |
| `CheckUserStatus` | Validates user account status | None |

---

## Route Protection

### Super Admin Routes
```php
Route::middleware(['auth', 'super.admin'])->prefix('super-admin')->group(function () {
    // Full system access
});
```

### Organization Admin Routes
```php
Route::middleware(['auth', 'role:org-admin|super-admin'])->prefix('organization')->group(function () {
    // Org-level access (super-admin can also access)
});
```

### Staff Dashboard Routes
```php
Route::middleware(['auth', 'role:coach|assistant-coach|head-coach'])->prefix('coach')->group(function () {
    // Coach-specific access
});
```

---

## Authorization Services

### PermissionEngine (NEW - Centralized)

Location: `/app/Core/Authorization/PermissionEngine.php`

Provides unified authorization checks:

```php
use App\Core\Authorization\PermissionEngine;

$engine = new PermissionEngine();

// Check basic permission
$check = $engine->checkPermission($user, 'edit', 'players');

// Check organization scope
$scope = $engine->checkOrganizationScope($user, $organization);

// Check role hierarchy
$role = $engine->checkRoleHierarchy($user, 'org-admin');

// Complete validation
$validation = $engine->validateAccess($user, 'edit', 'players', $organization, 'org-admin');
```

### RoleHierarchyService

Location: `/app/Services/RoleHierarchyService.php`

Handles role hierarchy, dashboard routing, and subscription-based role filtering.

---

## 403 Error Handling

### Legitimate 403 Triggers

| Source | Condition |
|--------|-----------|
| `RoleMiddleware` | User lacks required role |
| `AdminMiddleware` | User lacks admin/staff role |
| `CheckSuperAdmin` | Non-super-admin accessing super-admin route |
| Controller | Cross-organization data access |
| Controller | Insufficient permission for action |

### Prevention

- All controllers validate organization scope for non-super-admin users
- Cross-organization access is explicitly denied
- Permission checks use wildcard support (e.g., `players.*`)

---

## Security Guidelines

### Super Admin Authority

✅ **Super Admin CAN:**
- Access all organizations without restriction
- Override any role/permission
- Manage subscription plans
- Create/suspend/delete organizations
- View system-wide analytics

✅ **Super Admin MUST:**
- Still be authenticated
- Have `super-admin` role assigned
- Have approved account status

### Privilege Escalation Prevention

The system prevents lower-privileged users from escalating their privileges:

```php
// In RoleHierarchyService
public function canUserEscalatePrivilege(User $creator, string $targetRole): bool
{
    // Only same or higher priority roles can be assigned
    return $targetPriority <= $creatorHighestPriority;
}
```

---

## Folder Structure

```
app/
├── Core/
│   └── Authorization/
│       └── PermissionEngine.php    # NEW: Centralized authorization
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                  # Admin dashboard controllers
│   │   ├── SuperAdmin/             # Super admin controllers
│   │   ├── Organization/           # Org admin controllers
│   │   ├── Staff/                  # Staff dashboard controllers
│   │   └── ...
│   └── Middleware/
│       ├── CheckSuperAdmin.php     # Super admin validation
│       ├── AdminMiddleware.php      # Admin/staff validation
│       ├── RoleMiddleware.php       # Generic role validation
│       ├── TenantScope.php          # Organization scoping
│       └── ...
├── Models/
│   ├── User.php                    # hasRole(), hasPermission()
│   ├── Role.php                    # Role with permissions
│   ├── Permission.php               # Permission definitions
│   └── Organization.php             # Organization with subscription
└── Services/
    └── RoleHierarchyService.php     # Role hierarchy & routing
```

---

## Testing Checklist

- [ ] Super admin can access all organizations
- [ ] Org admin cannot access other organizations
- [ ] Staff cannot escalate privileges
- [ ] 403 errors only occur for legitimate reasons
- [ ] Dashboard routing works for all roles
- [ ] Subscription feature checks work correctly

---

## Last Updated

February 2026
