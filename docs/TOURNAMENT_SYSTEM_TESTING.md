# Tournament System Testing Documentation

## Overview

This document outlines the comprehensive testing strategy for the tournament management system, including dashboard functionality, governance features, and API endpoints.

## Test Categories

### 1. Dashboard Functionality Tests

#### 1.1 Admin Dashboard Tests

**Test File**: `tests/Feature/AdminDashboardTest.php`

**Test Cases**:
- ✅ Dashboard quick action buttons functionality
- ✅ Route parameter validation
- ✅ Permission-based access control
- ✅ Tournament creation workflow
- ✅ Navigation and user experience

**Quick Actions to Test**:
1. New Tournament → `route('admin.tournaments.create')`
2. All Tournaments → `route('admin.tournaments.index')`
3. Tournament Overview → `route('admin.tournaments.overview')`
4. Matches → `route('admin.tournaments.matches')`
5. Venues → `route('admin.tournaments.venues')`
6. Teams → `route('admin.tournaments.teams')`
7. Smart Tournament Engine → `route('admin.tournaments.engine')`
8. Match Scheduler → `route('admin.tournaments.scheduler')`

#### 1.2 Super Admin Dashboard Tests

**Test File**: `tests/Feature/SuperAdminDashboardTest.php`

**Test Cases**:
- ✅ Multi-organization dashboard functionality
- ✅ Super admin specific quick actions
- ✅ Organization management workflows
- ✅ System-wide governance access
- ✅ Analytics and reporting access

**Quick Actions to Test**:
1. Tournament Overview → `route('super-admin.tournaments.overview')`
2. Website Content → `route('super-admin.page-content.index')`
3. Tournaments → `route('super-admin.tournaments.index')`
4. Manage Organizations → `route('super-admin.organizations.index')`
5. Role Management → `route('super-admin.roles.index')`
6. Subscription Plans → `route('super-admin.plans.index')`
7. Analytics → `route('super-admin.analytics')`
8. Governance quick actions (6 buttons)

### 2. Route Testing

#### 2.1 Admin Routes (45 routes)

**Test File**: `tests/Feature/AdminRoutesTest.php`

**Route Groups to Test**:
- `admin.tournaments.*` (15 routes)
- `admin.governance.age-verification.*` (15 routes)
- `admin.governance.disciplinary.*` (15 routes)

**Test Cases**:
- ✅ Route accessibility with proper authentication
- ✅ Route parameters and validation
- ✅ Middleware application (auth, admin)
- ✅ 404 handling for invalid routes
- ✅ CSRF protection

#### 2.2 Super Admin Routes (35 routes)

**Test File**: `tests/Feature/SuperAdminRoutesTest.php`

**Route Groups to Test**:
- `super-admin.tournaments.*` (15 routes)
- `super-admin.governance.*` (20 routes)

**Test Cases**:
- ✅ Super admin role requirement
- ✅ Multi-organization access control
- ✅ Route parameter validation
- ✅ Organization isolation

#### 2.3 API Routes (10 routes)

**Test File**: `tests/Feature/ApiRoutesTest.php`

**API Endpoints to Test**:
- `api.governance.age-verification.*`
- `api.governance.disciplinary.*`
- `api.governance.appeals.*`
- `api.governance.protests.*`

**Test Cases**:
- ✅ API authentication
- ✅ JSON response format
- ✅ Error handling and status codes
- ✅ Data validation
- ✅ Rate limiting

### 3. Controller Testing

#### 3.1 Tournament Controllers

**Test Files**:
- `tests/Feature/AdminTournamentControllerTest.php`
- `tests/Feature/SuperAdminTournamentControllerTest.php`

**Methods to Test** (45+ methods):
- ✅ CRUD operations
- ✅ Tournament lifecycle management
- ✅ Team and player management
- ✅ Match scheduling
- ✅ Statistics and reporting
- ✅ Bulk operations

#### 3.2 Governance Controllers

**Test Files**:
- `tests/Feature/AgeVerificationControllerTest.php`
- `tests/Feature/DisciplinaryCaseControllerTest.php`
- `tests/Feature/AppealControllerTest.php`
- `tests/Feature/ProtestControllerTest.php`

**Methods to Test** (40+ methods):
- ✅ Age verification workflows
- ✅ Disciplinary case management
- ✅ Appeal submission and review
- ✅ Protest handling
- ✅ API endpoints

### 4. Integration Testing

#### 4.1 Dashboard Integration Tests

**Test File**: `tests/Feature/DashboardIntegrationTest.php`

**Integration Points**:
- ✅ Dashboard → Tournament creation
- ✅ Dashboard → Governance access
- ✅ Dashboard → Analytics
- ✅ Cross-controller navigation

#### 4.2 Multi-Organization Tests

**Test File**: `tests/Feature/MultiOrganizationTest.php`

**Test Cases**:
- ✅ Organization isolation
- ✅ Super admin cross-organization access
- ✅ Data separation
- ✅ Permission inheritance

### 5. API Testing

#### 5.1 REST API Tests

**Test File**: `tests/Feature/ApiTest.php`

**Endpoints to Test**:
- ✅ `GET /api/governance/age-verification/players/{player}/status`
- ✅ `GET /api/governance/age-verification/rules/{organization}/active`
- ✅ `POST /api/governance/age-verification/players/{player}/verify`
- ✅ `GET /api/governance/disciplinary/cases/{case}/status`
- ✅ `GET /api/governance/disciplinary/players/{player}/history`
- ✅ `GET /api/governance/disciplinary/suspensions/active`
- ✅ `GET /api/governance/appeals/cases/{case}/appeals`
- ✅ `GET /api/governance/appeals/players/{player}/appeals`
- ✅ `GET /api/governance/protests/matches/{match}/protests`
- ✅ `GET /api/governance/protests/teams/{team}/protests`

### 6. Unit Testing

#### 6.1 Model Tests

**Test Files**:
- `tests/Unit/TournamentTest.php`
- `tests/Unit/TournamentTeamTest.php`
- `tests/Unit/TournamentMatchTest.php`
- `tests/Unit/AgeAlertRuleTest.php`
- `tests/Unit/DisciplinaryCaseTest.php`
- `tests/Unit/AppealTest.php`
- `tests/Unit/ProtestTest.php`

**Test Cases**:
- ✅ Model relationships
- ✅ Validation rules
- ✅ Scopes and query builders
- ✅ Mutators and accessors
- ✅ Event listeners

#### 6.2 Service Tests

**Test Files**:
- `tests/Unit/TournamentServiceTest.php`
- `tests/Unit/GovernanceServiceTest.php`

**Test Cases**:
- ✅ Business logic validation
- ✅ Service method functionality
- ✅ Error handling
- ✅ Data processing

## Test Execution Commands

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suites
```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# Dashboard tests
php artisan test tests/Feature/AdminDashboardTest.php
php artisan test tests/Feature/SuperAdminDashboardTest.php

# Route tests
php artisan test tests/Feature/AdminRoutesTest.php
php artisan test tests/Feature/SuperAdminRoutesTest.php

# Controller tests
php artisan test tests/Feature/AdminTournamentControllerTest.php
php artisan test tests/Feature/SuperAdminTournamentControllerTest.php

# Governance tests
php artisan test tests/Feature/AgeVerificationControllerTest.php
php artisan test tests/Feature/DisciplinaryCaseControllerTest.php
php artisan test tests/Feature/AppealControllerTest.php
php artisan test tests/Feature/ProtestControllerTest.php

# API tests
php artisan test tests/Feature/ApiTest.php

# Integration tests
php artisan test tests/Feature/DashboardIntegrationTest.php
php artisan test tests/Feature/MultiOrganizationTest.php
```

### Generate Test Coverage
```bash
php artisan test --coverage
```

### Run Tests with Verbose Output
```bash
php artisan test --verbose
```

## Test Data Setup

### Test Database
```bash
# Create test database
php artisan migrate --database=testing

# Seed test data
php artisan db:seed --database=testing
```

### Test Factories
```php
// Create test organizations
Organization::factory()->count(5)->create();

// Create test tournaments
Tournament::factory()->count(10)->create();

// Create test users with roles
User::factory()->admin()->count(3)->create();
User::factory()->superAdmin()->count(2)->create();

// Create test players and teams
Player::factory()->count(50)->create();
Team::factory()->count(10)->create();
```

## Expected Test Results

### Success Criteria
- ✅ 100% route coverage
- ✅ 100% controller method coverage
- ✅ 100% dashboard quick action functionality
- ✅ 100% governance system functionality
- ✅ 100% API endpoint functionality
- ✅ 100% authorization and security
- ✅ 100% multi-organization isolation

### Performance Benchmarks
- ✅ Dashboard loads in < 2 seconds
- ✅ API responses in < 500ms
- ✅ Database queries optimized
- ✅ Memory usage within limits

### Security Requirements
- ✅ All routes protected by authentication
- ✅ Role-based access control enforced
- ✅ CSRF protection active
- ✅ Input validation comprehensive
- ✅ SQL injection prevention
- ✅ XSS protection active

## Test Environment Setup

### Environment Variables
```env
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync

MAIL_MAILER=log
LOG_CHANNEL=stderr
```

### Browser Testing (Optional)
```bash
# Install Laravel Dusk
composer require --dev laravel/dusk

# Run browser tests
php artisan dusk
```

## Continuous Integration

### GitHub Actions Workflow
```yaml
name: Tournament System Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: test_db
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: mbstring, dom, fileinfo
          coverage: xdebug
      - name: Install dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist
      - name: Setup database
        run: |
          cp .env.testing .env
          php artisan key:generate
          php artisan migrate --database=testing
      - name: Run tests
        run: vendor/bin/phpunit
      - name: Run feature tests
        run: php artisan test --testsuite=Feature
      - name: Run API tests
        run: php artisan test tests/Feature/ApiTest.php
```

## Test Maintenance

### Regular Test Updates
- ✅ Update tests when adding new features
- ✅ Update tests when changing existing functionality
- ✅ Remove obsolete tests
- ✅ Add performance tests for new features

### Test Documentation
- ✅ Document new test cases
- ✅ Update test descriptions
- ✅ Maintain test data documentation
- ✅ Document test environment setup

## Troubleshooting

### Common Issues
1. **Route not found**: Check route names and parameters
2. **Authentication failed**: Verify middleware and user roles
3. **Database errors**: Check migrations and test data
4. **API errors**: Verify JSON format and validation
5. **Performance issues**: Check query optimization

### Debug Commands
```bash
# Check route list
php artisan route:list

# Check middleware
php artisan route:list --middleware

# Check database connections
php artisan tinker
>>> DB::connection()->getPdo();

# Check logs
tail -f storage/logs/laravel.log
```

This comprehensive testing strategy ensures the tournament system is thoroughly validated and ready for production use.
