# Tournament System Developer Guide

## Overview

This guide provides comprehensive information for developers working with the tournament management system. It covers code structure, architecture, API development, testing strategies, and extension guidelines.

## Table of Contents

1. [System Architecture](#system-architecture)
2. [Code Structure](#code-structure)
3. [Database Design](#database-design)
4. [API Development](#api-development)
5. [Testing Strategies](#testing-strategies)
6. [Extension Guidelines](#extension-guidelines)
7. [Deployment](#deployment)
8. [Performance Optimization](#performance-optimization)
9. [Security Best Practices](#security-best-practices)

## System Architecture

### Laravel Framework

The tournament system is built on Laravel 10+ with the following architecture:

#### MVC Pattern
- **Models**: Eloquent ORM for database interactions
- **Controllers**: Business logic and request handling
- **Views**: Blade templates for frontend rendering
- **Routes**: HTTP request routing and middleware

#### Service Layer
- **Services**: Business logic encapsulation
- **Repositories**: Data access abstraction
- **Helpers**: Utility functions and common operations

#### Event System
- **Events**: System events and notifications
- **Listeners**: Event handlers and processing
- **Jobs**: Background task processing
- **Queues**: Task queue management

### Microservices Architecture

#### Core Services
- **Tournament Service**: Tournament management
- **Governance Service**: Age verification, disciplinary, appeals, protests
- **User Service**: User and role management
- **Organization Service**: Multi-organization support

#### API Services
- **RESTful APIs**: Public API endpoints
- **Webhooks**: Real-time notifications
- **Authentication**: JWT and Sanctum tokens

### Technology Stack

#### Backend
- **PHP 8.1+**: Primary programming language
- **Laravel 10+**: Web framework
- **MySQL 8.0+**: Primary database
- **Redis**: Caching and queue management
- **Composer**: Dependency management

#### Frontend
- **Blade Templates**: Server-side rendering
- **Tailwind CSS**: Styling framework
- **Alpine.js**: JavaScript framework
- **Vite**: Build tool and asset management

#### Development Tools
- **PHPUnit**: Testing framework
- **Laravel Dusk**: Browser testing
- **Laravel Mix**: Asset compilation
- **Git**: Version control

## Code Structure

### Directory Structure

```
app/
├── Console/              # Artisan commands
├── Core/                 # Core system functionality
│   └── Authorization/    # Custom authorization logic
├── Http/
│   ├── Controllers/      # Request handlers
│   ├── Middleware/       # Request middleware
│   └── Requests/         # Form request validation
├── Models/               # Eloquent models
├── Policies/             # Authorization policies
├── Providers/            # Service providers
├── Services/             # Business logic services
└── View/                 # View composers and helpers

database/
├── factories/            # Model factories
├── migrations/           # Database migrations
└── seeders/              # Database seeders

routes/
├── auth.php              # Authentication routes
├── console.php           # Console routes
├── governance.php        # Governance routes
├── team.php              # Team routes
├── web_leaders.php       # Leader routes
└── web.php               # Web application routes

tests/
├── Feature/              # Feature tests
└── Unit/                 # Unit tests

resources/
├── css/                  # CSS stylesheets
├── js/                   # JavaScript files
└── views/                # Blade templates
```

### Controller Organization

#### Admin Controllers
- **AdminTournamentController**: Tournament management
- **AdminTeamController**: Team management
- **AdminPlayerController**: Player management
- **AdminMatchController**: Match management

#### Super Admin Controllers
- **SuperAdminTournamentController**: System-wide tournaments
- **SuperAdminOrganizationController**: Organization management
- **SuperAdminUserController**: User management
- **SuperAdminAnalyticsController**: Analytics and reporting

#### Governance Controllers
- **AgeVerificationController**: Age verification management
- **DisciplinaryCaseController**: Disciplinary case management
- **AppealController**: Appeal management
- **ProtestController**: Protest management

### Model Relationships

#### Core Models
```php
// Tournament Model
class Tournament extends Model
{
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function teams()
    {
        return $this->hasMany(TournamentTeam::class);
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }
}

// Player Model
class Player extends Model
{
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function ageVerifications()
    {
        return $this->hasMany(PlayerAgeVerification::class);
    }

    public function disciplinaryCases()
    {
        return $this->hasMany(DisciplinaryCase::class);
    }
}
```

## Database Design

### Entity Relationship Diagram

#### Core Entities
- **Organizations**: Multi-tenant support
- **Users**: User management with roles
- **Tournaments**: Tournament lifecycle management
- **Teams**: Team registration and management
- **Players**: Player registration and verification
- **Matches**: Match scheduling and results

#### Governance Entities
- **AgeAlertRules**: Age verification rules
- **DisciplinaryCases**: Disciplinary case management
- **Appeals**: Appeal submission and review
- **Protests**: Protest filing and resolution
- **PlayerSuspensions**: Suspension tracking

### Database Schema

#### Key Tables
```sql
-- Organizations Table
CREATE TABLE organizations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    description TEXT,
    status ENUM('active', 'inactive', 'suspended', 'trial', 'pending') DEFAULT 'trial',
    subscription_plan_id INT,
    max_users INT DEFAULT 10,
    max_players INT DEFAULT 100,
    billing_cycle ENUM('monthly', 'yearly') DEFAULT 'monthly',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tournaments Table
CREATE TABLE tournaments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    organization_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    season VARCHAR(50),
    competition_format ENUM('league', 'round_robin', 'league_cup', 'knockout', 'knockout_plus', 'groups_knockout', 'double_elimination'),
    estimated_matches INT,
    description TEXT,
    registration_deadline DATE,
    squad_limit INT DEFAULT 25,
    min_players INT DEFAULT 11,
    max_teams INT,
    status ENUM('draft', 'open', 'closed', 'ongoing', 'completed', 'cancelled') DEFAULT 'draft',
    start_date DATE,
    end_date DATE,
    venue VARCHAR(255),
    rules TEXT,
    is_public BOOLEAN DEFAULT true,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organization_id) REFERENCES organizations(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### Indexes and Optimization

#### Performance Indexes
```sql
-- Organization-based queries
CREATE INDEX idx_tournaments_organization_status ON tournaments(organization_id, status);
CREATE INDEX idx_players_organization ON players(organization_id);

-- Date-based queries
CREATE INDEX idx_tournaments_dates ON tournaments(start_date, end_date);
CREATE INDEX idx_matches_date ON tournament_matches(kickoff_time);

-- Status-based queries
CREATE INDEX idx_disciplinary_status ON disciplinary_cases(status);
CREATE INDEX idx_appeals_status ON appeals(status);
```

## API Development

### RESTful API Design

#### API Versioning
```php
// API routes with versioning
Route::prefix('v1')->group(function () {
    Route::apiResource('tournaments', TournamentController::class);
    Route::apiResource('players', PlayerController::class);
    Route::apiResource('teams', TeamController::class);
});
```

#### Response Format
```php
// Standard API response structure
return response()->json([
    'success' => true,
    'data' => $data,
    'message' => 'Operation successful',
    'meta' => [
        'timestamp' => now()->toIso8601String(),
        'version' => config('app.version')
    ]
]);
```

### Authentication

#### Sanctum Token Authentication
```php
// API authentication middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Protected routes
    Route::apiResource('governance/age-verification', AgeVerificationController::class);
});
```

#### Role-Based Access Control
```php
// Custom middleware for role checking
public function handle($request, Closure $next, ...$roles)
{
    if (! $request->user()->hasAnyRole($roles)) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    return $next($request);
}
```

### API Documentation

#### OpenAPI/Swagger Integration
```php
/**
 * @OA\Get(
 *     path="/api/governance/age-verification/players/{player}/status",
 *     summary="Get player age verification status",
 *     @OA\Parameter(
 *         name="player",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(ref="#/components/schemas/PlayerStatus")
 *     )
 * )
 */
```

## Testing Strategies

### Unit Testing

#### Model Testing
```php
public function test_tournament_belongs_to_organization()
{
    $organization = Organization::factory()->create();
    $tournament = Tournament::factory()->create([
        'organization_id' => $organization->id
    ]);

    $this->assertTrue($tournament->organization->is($organization));
}

public function test_tournament_validation_rules()
{
    $data = [
        'name' => '',
        'organization_id' => 999999, // Non-existent
        'start_date' => 'invalid-date'
    ];

    $response = $this->postJson('/api/tournaments', $data);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'organization_id', 'start_date']);
}
```

#### Controller Testing
```php
public function test_admin_can_create_tournament()
{
    $admin = User::factory()->admin()->create();
    
    $data = [
        'name' => 'Test Tournament',
        'organization_id' => $admin->organization_id,
        'start_date' => now()->addMonth(),
        'end_date' => now()->addMonths(2)
    ];

    $response = $this->actingAs($admin)
        ->postJson('/api/tournaments', $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('tournaments', ['name' => 'Test Tournament']);
}
```

### Feature Testing

#### Integration Testing
```php
public function test_tournament_lifecycle()
{
    $admin = User::factory()->admin()->create();
    $tournament = Tournament::factory()->create([
        'organization_id' => $admin->organization_id
    ]);

    // Test registration opening
    $response = $this->actingAs($admin)
        ->patchJson("/api/tournaments/{$tournament->id}/open-registration");
    $response->assertStatus(200);

    // Test team registration
    $team = Team::factory()->create(['organization_id' => $admin->organization_id]);
    $response = $this->actingAs($admin)
        ->postJson("/api/tournaments/{$tournament->id}/teams", [
            'team_id' => $team->id
        ]);
    $response->assertStatus(201);

    // Test tournament start
    $response = $this->actingAs($admin)
        ->patchJson("/api/tournaments/{$tournament->id}/start");
    $response->assertStatus(200);
}
```

### Browser Testing

#### Laravel Dusk
```php
public function test_admin_dashboard_navigation()
{
    $this->browse(function (Browser $browser) {
        $browser->loginAs(User::factory()->admin()->create())
            ->visit('/admin/dashboard')
            ->assertSee('Tournament Management')
            ->clickLink('New Tournament')
            ->assertPathIs('/admin/tournaments/create')
            ->type('name', 'Test Tournament')
            ->press('Create Tournament')
            ->assertSee('Tournament created successfully');
    });
}
```

## Extension Guidelines

### Adding New Features

#### 1. Database Changes
```php
// Create migration
php artisan make:migration add_field_to_table --table=table_name

// Migration example
public function up()
{
    Schema::table('tournaments', function (Blueprint $table) {
        $table->string('new_field')->nullable();
        $table->index('new_field');
    });
}

public function down()
{
    Schema::table('tournaments', function (Blueprint $table) {
        $table->dropColumn('new_field');
    });
}
```

#### 2. Model Updates
```php
// Update model
class Tournament extends Model
{
    protected $fillable = [
        'name',
        'organization_id',
        'new_field', // Add new field
    ];

    protected $casts = [
        'new_field' => 'string',
    ];

    // Add new relationships if needed
    public function newRelationship()
    {
        return $this->hasMany(NewModel::class);
    }
}
```

#### 3. Controller Updates
```php
// Add new methods to controller
public function newFeature(Tournament $tournament)
{
    // Implementation
    return response()->json([
        'success' => true,
        'data' => $tournament->newRelationship
    ]);
}

// Add route
Route::get('/tournaments/{tournament}/new-feature', [TournamentController::class, 'newFeature'])
    ->middleware(['auth', 'admin']);
```

#### 4. Testing
```php
// Add tests
public function test_new_feature()
{
    $admin = User::factory()->admin()->create();
    $tournament = Tournament::factory()->create([
        'organization_id' => $admin->organization_id
    ]);

    $response = $this->actingAs($admin)
        ->getJson("/api/tournaments/{$tournament->id}/new-feature");

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
}
```

### Custom Validation Rules

#### Creating Custom Rules
```php
// Create custom rule
php artisan make:rule ValidTournamentDate

class ValidTournamentDate implements Rule
{
    public function passes($attribute, $value)
    {
        // Custom validation logic
        return $value >= now()->addDays(7);
    }

    public function message()
    {
        return 'The :attribute must be at least 7 days in the future.';
    }
}

// Use in controller
$validator = Validator::make($request->all(), [
    'start_date' => ['required', new ValidTournamentDate],
]);
```

### Service Providers

#### Creating Custom Service Providers
```php
// Create service provider
php artisan make:provider TournamentServiceProvider

class TournamentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TournamentService::class, function ($app) {
            return new TournamentService();
        });
    }

    public function boot()
    {
        // Register routes, views, etc.
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'tournament');
    }
}

// Register in config/app.php
'providers' => [
    // Other providers
    App\Providers\TournamentServiceProvider::class,
];
```

## Deployment

### Environment Configuration

#### Environment Variables
```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tournament_system
DB_USERNAME=root
DB_PASSWORD=password

# Cache
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# API
API_TOKEN_EXPIRATION=3600
RATE_LIMITER_ENABLED=true

# Security
APP_KEY=your-app-key-here
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_SECURE_COOKIE=false
```

### Deployment Scripts

#### Production Deployment
```bash
#!/bin/bash
# deploy.sh

echo "Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
php artisan queue:restart

echo "Deployment completed successfully!"
```

#### Docker Deployment
```dockerfile
# Dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install PHP dependencies
RUN composer install --optimize-autoloader

# Create storage and cache directories
RUN mkdir -p storage/logs && \
    mkdir -p bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
```

## Performance Optimization

### Database Optimization

#### Query Optimization
```php
// Use eager loading to prevent N+1 queries
$tournaments = Tournament::with(['organization', 'teams.team', 'matches'])
    ->where('status', 'ongoing')
    ->paginate(20);

// Use select statements for specific fields
$tournaments = Tournament::select('id', 'name', 'start_date', 'status')
    ->where('status', '!=', 'draft')
    ->get();

// Use chunking for large datasets
Tournament::chunk(1000, function ($tournaments) {
    foreach ($tournaments as $tournament) {
        // Process each tournament
    }
});
```

#### Indexing Strategy
```sql
-- Composite indexes for common query patterns
CREATE INDEX idx_tournaments_org_status_dates ON tournaments(organization_id, status, start_date);

-- Full-text indexes for search
ALTER TABLE tournaments ADD FULLTEXT(name, description);

-- Foreign key indexes
CREATE INDEX idx_tournament_teams_tournament_id ON tournament_teams(tournament_id);
CREATE INDEX idx_tournament_teams_team_id ON tournament_teams(team_id);
```

### Caching Strategies

#### Redis Caching
```php
// Cache tournament data
public function getTournamentWithCache($id)
{
    return Cache::remember("tournament.{$id}", 3600, function () use ($id) {
        return Tournament::with(['teams', 'matches', 'standings'])->find($id);
    });
}

// Cache expensive calculations
public function getTournamentStatistics($tournamentId)
{
    return Cache::tags(['tournament', 'statistics'])
        ->remember("tournament.{$tournamentId}.stats", 1800, function () use ($tournamentId) {
            // Expensive calculation
            return $this->calculateStatistics($tournamentId);
        });
}

// Cache clearing
public function invalidateTournamentCache($tournamentId)
{
    Cache::tags(['tournament'])->forget("tournament.{$tournamentId}");
    Cache::tags(['tournament', 'statistics'])->forget("tournament.{$tournamentId}.stats");
}
```

#### Query Caching
```php
// Enable query caching
DB::enableQueryLog();

// Cache query results
$standings = Cache::remember("tournament.{$tournamentId}.standings", 600, function () use ($tournamentId) {
    return TournamentStanding::where('tournament_id', $tournamentId)
        ->with('team')
        ->orderBy('points', 'desc')
        ->get();
});
```

### Frontend Optimization

#### Asset Optimization
```javascript
// vite.config.js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', 'vue-router', 'vuex'],
                    charts: ['chart.js', 'chartjs-adapter-date-fns']
                }
            }
        }
    }
})
```

#### Image Optimization
```php
// Use responsive images
<img src="{{ $player->avatar->thumbnail() }}"
     srcset="{{ $player->avatar->srcset() }}"
     sizes="(max-width: 600px) 100vw, 50vw"
     alt="{{ $player->name }}">

// Lazy loading
<img loading="lazy" 
     src="{{ $player->avatar->thumbnail() }}"
     alt="{{ $player->name }}">
```

## Security Best Practices

### Input Validation

#### Request Validation
```php
class TournamentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:tournaments,name,NULL,id,organization_id,' . $this->user()->organization_id,
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_teams' => 'integer|min:2|max:100',
            'registration_deadline' => 'nullable|date|before:start_date',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'A tournament with this name already exists for your organization.',
            'start_date.after' => 'Start date must be in the future.',
            'end_date.after' => 'End date must be after start date.',
        ];
    }
}
```

### Authentication and Authorization

#### Policy Implementation
```php
class TournamentPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('super-admin');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Tournament $tournament)
    {
        return $user->organization_id === $tournament->organization_id &&
               $user->hasRole('admin');
    }

    public function delete(User $user, Tournament $tournament)
    {
        return $user->organization_id === $tournament->organization_id &&
               $user->hasRole('admin') &&
               $tournament->status === 'draft';
    }
}
```

### Security Headers

#### Middleware for Security Headers
```php
class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
```

### Rate Limiting

#### API Rate Limiting
```php
// RouteServiceProvider.php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Custom rate limiting
RateLimiter::for('tournament-creation', function (Request $request) {
    return Limit::perHour(10)->by($request->user()->id);
});
```

## Conclusion

This developer guide provides comprehensive information for working with the tournament management system. Follow these guidelines for consistent, secure, and performant development. Regularly review and update code to maintain system quality and security.

For additional support and questions:
- **Developer Documentation**: docs.tournament-system.com
- **Code Repository**: github.com/tournament-system
- **Developer Forum**: forum.tournament-system.com
- **Support Email**: developers@tournament-system.com

---

**Last Updated**: March 2024  
**Version**: 2.1  
**Next Review**: September 2024
