# Security Implementation Guide

## Overview

This document provides a comprehensive guide to the RBAC (Role-Based Access Control) and multi-tenant security implementation in the Vipers Academy system.

## Security Architecture

### 1. Role Hierarchy

The system implements a strict role hierarchy:

```
SuperAdmin (Highest)
    ├── Admin
    │   ├── Tournament Director
    │   │   ├── Team Manager
    │   │   │   ├── Coach
    │   │   │   │   ├── Player
    │   │   │   │   └── Parent
    │   │   │   └── Scout
    │   │   └── Scout
    │   └── Scout
    └── Scout
```

### 2. Organization Isolation

Each organization operates as a separate tenant with complete data isolation:

- **SuperAdmin**: Can access all organizations
- **All other roles**: Restricted to their assigned organization only
- **Data queries**: Automatically scoped by organization_id

## Security Components

### 1. Permission Service (`app/Services/PermissionService.php`)

Centralized permission management with methods for:

- Role hierarchy validation
- Organization access control
- CRUD operation permissions
- Resource-specific permissions

**Key Methods:**
- `canAccessOrganization()`: Check organization access
- `canManageTournament()`: Check tournament management rights
- `hasRoleOrHigher()`: Validate role hierarchy
- `canCRUD()`: Check CRUD permissions

### 2. Organization Scope Middleware (`app/Http/Middleware/OrganizationScopeMiddleware.php`)

Automatically applies organization scoping to all database queries:

- **SuperAdmin**: Bypasses all scoping
- **Other users**: Queries scoped to their organization_id
- **Global scope**: Applied to all Eloquent models

### 3. Organization Scoped Trait (`app/Traits/OrganizationScoped.php`)

Trait for models requiring organization isolation:

```php
use OrganizationScoped;

// Automatically scopes queries to user's organization
$teams = Team::all(); // Only returns teams from user's org

// Manual scoping
$teams = Team::organization($orgId)->get();
```

### 4. Policy Classes (`app/Policies/`)

Authorization policies for resource access:

- `OrganizationPolicy`: Controls organization management
- Additional policies for tournaments, teams, players

**Usage:**
```php
// In controllers
$this->authorize('update', $organization);

// In views
@can('update', $organization)
    <!-- Show edit button -->
@endcan
```

## Security Enforcement

### 1. Database Query Scoping

All queries are automatically scoped:

```php
// Without scoping - DANGEROUS
$teams = Team::all(); // Returns all teams

// With scoping - SECURE
$teams = Team::all(); // Returns only user's organization teams
```

### 2. Route Protection

Routes are protected using middleware:

```php
Route::middleware(['auth', 'organization.scope'])->group(function () {
    Route::resource('tournaments', TournamentController::class);
});
```

### 3. Controller Authorization

Controllers use policies for authorization:

```php
public function update(Request $request, Tournament $tournament)
{
    $this->authorize('update', $tournament);
    // ... update logic
}
```

### 4. Frontend Permission Checks

Views use Blade directives for permission checks:

```blade
@can('create', App\Models\Tournament::class)
    <a href="{{ route('tournaments.create') }}">Create Tournament</a>
@endcan

@can('update', $tournament)
    <a href="{{ route('tournaments.edit', $tournament) }}">Edit</a>
@endcan
```

## Security Testing

### 1. Automated Tests (`tests/Feature/Security/RBACTest.php`)

Comprehensive test suite covering:

- Organization isolation
- Role hierarchy enforcement
- Cross-organization access prevention
- Permission escalation prevention
- URL manipulation prevention
- API security

### 2. Security Validation Script (`scripts/validate_security.php`)

Standalone script for security validation:

```bash
php scripts/validate_security.php
```

**Test Coverage:**
- Organization isolation
- Role hierarchy
- CRUD permissions
- SuperAdmin privileges
- URL manipulation prevention

## Security Best Practices

### 1. Never Bypass Scoping

❌ **Incorrect:**
```php
// Bypasses organization scoping
$teams = Team::withoutGlobalScope('organization')->get();
```

✅ **Correct:**
```php
// Respects organization scoping
$teams = Team::all();
```

### 2. Always Use Authorization

❌ **Incorrect:**
```php
public function update(Tournament $tournament)
{
    // No authorization check
    $tournament->update($request->all());
}
```

✅ **Correct:**
```php
public function update(Request $request, Tournament $tournament)
{
    $this->authorize('update', $tournament);
    $tournament->update($request->all());
}
```

### 3. Validate User Organization

❌ **Incorrect:**
```php
// Direct access without validation
$player = Player::find($id);
```

✅ **Correct:**
```php
// Automatic scoping via trait
$player = Player::find($id); // Only finds players in user's org
```

### 4. Use Policy Methods

❌ **Incorrect:**
```php
if ($user->hasRole('admin')) {
    // Grant access
}
```

✅ **Correct:**
```php
if ($this->authorize('manage', $resource)) {
    // Grant access
}
```

## Security Monitoring

### 1. Permission Logging

All permission checks are logged:

```php
$permissionService->logPermissionCheck(
    $user, 
    'access_tournament', 
    $tournament, 
    $granted
);
```

### 2. Audit Trail

System maintains audit logs for:
- Permission checks
- Data access attempts
- Role changes
- Organization switches

### 3. Security Alerts

Monitor for:
- Failed permission checks
- Cross-organization access attempts
- Role escalation attempts
- Unusual access patterns

## Common Security Vulnerabilities

### 1. Direct Model Access

**Vulnerability:** Bypassing authorization by directly accessing models

**Prevention:** Use policies and middleware consistently

### 2. URL Manipulation

**Vulnerability:** Changing URL parameters to access unauthorized data

**Prevention:** Server-side validation on every request

### 3. Parameter Tampering

**Vulnerability:** Modifying request parameters to escalate privileges

**Prevention:** Validate all input and use organization scoping

### 4. Session Hijacking

**Vulnerability:** Unauthorized access using stolen sessions

**Prevention:** Secure session management and monitoring

## Security Configuration

### 1. Middleware Registration

Ensure middleware is registered in `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\OrganizationScopeMiddleware::class,
    ],
];
```

### 2. Policy Registration

Register policies in `AuthServiceProvider`:

```php
protected $policies = [
    Organization::class => OrganizationPolicy::class,
    Tournament::class => TournamentPolicy::class,
    // ... other policies
];
```

### 3. Model Trait Usage

Apply the OrganizationScoped trait to all tenant models:

```php
class Tournament extends Model
{
    use OrganizationScoped;
    
    protected $fillable = [
        'name',
        'organization_id',
        // ... other fields
    ];
}
```

## Security Review Checklist

- [ ] All routes use organization scope middleware
- [ ] All controllers use authorization policies
- [ ] All models use OrganizationScoped trait
- [ ] No direct database queries bypass scoping
- [ ] Frontend uses @can directives for permissions
- [ ] SuperAdmin access is logged and monitored
- [ ] Cross-organization access is prevented
- [ ] Role hierarchy is enforced
- [ ] Security tests pass
- [ ] Permission logging is enabled

## Emergency Procedures

### 1. Security Breach Response

1. **Immediate Actions:**
   - Disable affected user accounts
   - Review access logs
   - Identify scope of breach

2. **Investigation:**
   - Analyze permission logs
   - Check for data exfiltration
   - Identify vulnerability

3. **Remediation:**
   - Patch security holes
   - Update access controls
   - Notify affected users

### 2. Role Escalation Response

1. **Detection:**
   - Monitor for unusual permission requests
   - Review role assignment logs
   - Check for privilege escalation attempts

2. **Response:**
   - Revoke unauthorized privileges
   - Audit user activities
   - Strengthen role validation

## Conclusion

This security implementation provides comprehensive protection against common web application vulnerabilities while maintaining usability and performance. Regular security audits and testing are essential to maintain system integrity.
