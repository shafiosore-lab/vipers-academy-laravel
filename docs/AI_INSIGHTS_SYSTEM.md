# AI Insights System Documentation

## Version 1.0 - Investment-Ready AI-Powered Analytics Platform

---

## Table of Contents

1. [System Overview](#system-overview)
2. [Architecture](#architecture)
3. [Database Schema](#database-schema)
4. [API Endpoints](#api-endpoints)
5. [Configuration](#configuration)
6. [Caching Strategy](#caching-strategy)
7. [Scheduled Jobs](#scheduled-jobs)
8. [Frontend Components](#frontend-components)
9. [Performance Metrics](#performance-metrics)
10. [Extensibility Guide](#extensibility-guide)
11. [Installation & Setup](#installation--setup)

---

## System Overview

The AI Insights System is a comprehensive, scalable platform for generating and delivering AI-powered analytics for player performance data. Designed with enterprise-grade considerations, it serves as Version 1.0 of a comprehensive AI suite that will grow increasingly sophisticated as data volume increases.

### Key Features

- **Dynamic Data Pipeline**: Replaces static data with living data points that evolve with new information
- **Automated Update Protocol**: Scheduled jobs trigger data refresh cycles every Friday at 2:00 AM (configurable)
- **Data Freshness Indicators**: Visual indicators showing when data was last updated
- **Performance Metrics Dashboard**: Built-in analytics tracking system usage, processing times, and user engagement
- **Microservices-Ready Architecture**: Clear API contracts enabling future expansion
- **Plugin Architecture**: Modular design allowing future AI module integration

---

## Architecture

### Core Components

```
┌─────────────────────────────────────────────────────────────────┐
│                      AI Insights System                          │
├─────────────────────────────────────────────────────────────────┤
│  Presentation Layer                                             │
│  ├── Frontend Components (Modal-based UI)                      │
│  ├── API Endpoints (RESTful)                                   │
│  └── Admin Dashboard                                           │
├─────────────────────────────────────────────────────────────────┤
│  Service Layer                                                 │
│  ├── AiInsightsService (Main service)                          │
│  ├── AiInsightsGenerator (Insight generation)                  │
│  └── AiStatisticsService (Statistics extraction)               │
├─────────────────────────────────────────────────────────────────┤
│  Data Layer                                                    │
│  ├── Models (AiInsight, AiInsightsDataSource, etc.)            │
│  ├── Database Migrations                                       │
│  └── Cache Manager                                             │
├─────────────────────────────────────────────────────────────────┤
│  Job Layer                                                     │
│  ├── Scheduled Commands                                        │
│  └── Queue Workers                                             │
└─────────────────────────────────────────────────────────────────┘
```

### Data Flow Architecture

```
Source Systems → Processing Layers → Presentation Layer
     │                  │                    │
     ▼                  ▼                    ▼
┌─────────┐      ┌─────────────┐      ┌─────────────┐
│  Game   │      │  Extract    │      │   Modal     │
│  Stats  │ ───► │  Transform  │ ───► │   UI        │
└─────────┘      │    Load     │      └─────────────┘
┌─────────┐      └─────────────┘      ┌─────────────┐
│Training │              │             │  API JSON   │
│  Data   │ ─────────────┤             └─────────────┘
└─────────┘              │             ┌─────────────┐
┌─────────┐              │             │  Admin      │
│External │ ─────────────┴             │  Dashboard  │
│  APIs   │                            └─────────────┘
└─────────┘
```

---

## Database Schema

### Tables Created

#### 1. `ai_insights` - Core Insights Storage

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| player_id | BIGINT | Foreign key to players table |
| insight_type | VARCHAR(50) | Type: strength, development, trend, style, prediction |
| insight_content | TEXT | The actual insight text |
| insight_data | JSON | Structured data for visualizations |
| confidence_level | VARCHAR(20) | low, medium, high, very_high |
| confidence_score | DECIMAL(5,2) | 0-100 numeric confidence |
| data_sources | JSON | Array of data sources used |
| data_points_used | INT | Number of data points analyzed |
| generated_at | TIMESTAMP | When insight was generated |
| valid_until | TIMESTAMP | When insight expires |
| version | VARCHAR(20) | AI model version used |
| model_name | VARCHAR(50) | Name of AI model |
| generation_time_ms | INT | Time to generate (ms) |
| is_active | BOOLEAN | Whether insight is active |
| is_manually_overridden | BOOLEAN | Whether manually edited |

#### 2. `ai_insights_data_sources` - Data Source Tracking

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| player_id | BIGINT | Foreign key to players table |
| source_type | VARCHAR(50) | Type: game_stats, training, biometric, etc. |
| source_name | VARCHAR(100) | Name of the data source |
| source_identifier | VARCHAR(100) | External ID or reference |
| data_uploaded_at | TIMESTAMP | When new data was uploaded |
| data_points_count | INT | Number of data points |
| is_active | BOOLEAN | Whether source is active |
| auto_sync_enabled | BOOLEAN | Whether auto-sync is enabled |
| sync_frequency | VARCHAR(20) | hourly, daily, weekly, manual |

#### 3. `ai_insights_metrics` - Performance Metrics

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| metric_type | VARCHAR(50) | Type: prediction_accuracy, processing_time, etc. |
| metric_name | VARCHAR(100) | Name of the metric |
| metric_value | DECIMAL(15,4) | The metric value |
| metric_unit | VARCHAR(20) | Unit: percentage, milliseconds, count |
| player_id | BIGINT | Optional player association |
| recorded_at | TIMESTAMP | When metric was recorded |
| period | VARCHAR(20) | realtime, hourly, daily, weekly |

#### 4. `ai_insights_jobs` - Job Scheduling

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| job_type | VARCHAR(50) | Type: refresh, generate, sync, archive |
| job_status | VARCHAR(20) | pending, running, completed, failed |
| players_processed | INT | Number of players processed |
| insights_generated | INT | Number of insights generated |
| execution_time_ms | INT | Total execution time |
| started_at | TIMESTAMP | Job start time |
| completed_at | TIMESTAMP | Job completion time |
| next_scheduled_at | TIMESTAMP | Next scheduled run |
| is_recurring | BOOLEAN | Whether job is recurring |
| recurrence_pattern | VARCHAR(100) | Cron expression or pattern |

#### 5. `ai_insights_analytics` - User Engagement

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| player_id | BIGINT | Foreign key to players table |
| insight_type | VARCHAR(50) | Type of insight viewed |
| user_type | VARCHAR(20) | visitor, registered, admin, scout |
| action | VARCHAR(50) | view, expand, share, export |
| view_duration_ms | INT | Time spent viewing |
| session_id | VARCHAR(100) | User session identifier |

---

## API Endpoints

### Base URL
```
/api/ai-insights
```

### Endpoints

#### System Status
```http
GET /api/ai-insights/system/status
```
Returns system health and performance metrics.

#### Player Insights
```http
GET /api/ai-insights/players/{player_id}
```
Returns all insights for a player.

```http
GET /api/ai-insights/players/{player_id}/{type}
```
Returns specific insight type (strength, development, trend, style, prediction).

```http
POST /api/ai-insights/players/{player_id}/refresh
```
Triggers insights refresh for a player.

```http
GET /api/ai-insights/players/{player_id}/freshness/status
```
Returns data freshness status.

```http
GET /api/ai-insights/players/{player_id}/metrics/engagement
```
Returns engagement metrics.

#### Data Sources
```http
POST /api/ai-insights/players/{player_id}/data-sources
```
Registers a new data source.

```http
POST /api/ai-insights/data-sources/{source_id}/upload
```
Records data upload for a source.

### Response Format

```json
{
    "success": true,
    "data": {
        "player_id": 1,
        "insights": [...],
        "insight_types": {...}
    },
    "meta": {
        "generated_at": "2024-01-15T10:30:00Z",
        "version": "1.0"
    }
}
```

---

## Configuration

### Environment Variables

```env
# AI Insights Model
AI_INSIGHTS_MODEL_NAME=vipers-analyst-v1
AI_INSIGHTS_MODEL_VERSION=1.0.0
AI_INSIGHTS_TEMPERATURE=0.3
AI_INSIGHTS_MAX_TOKENS=500

# Refresh Settings
AI_INSIGHTS_REFRESH_PATTERN=weekly_friday_2am
AI_INSIGHTS_AUTO_REFRESH=true
AI_INSIGHTS_REFRESH_DAY=friday

# Cache Settings
AI_INSIGHTS_CACHE_ENABLED=true
AI_INSIGHTS_CACHE_TTL=3600
AI_INSIGHTS_PLAYER_CACHE_TTL=3600

# Metrics
AI_INSIGHTS_METRICS_ENABLED=true
AI_INSIGHTS_RATE_LIMIT=60

# Logging
AI_INSIGHTS_LOG_LEVEL=info
```

### Config File (`config/ai-insights.php`)

Key configuration sections:
- `model`: AI model settings
- `refresh`: Automated refresh settings
- `cache`: Caching configuration
- `data_sources`: Data source settings
- `metrics`: Metrics tracking settings
- `api`: API configuration
- `frontend`: Frontend display settings
- `plugins`: Plugin architecture settings
- `investment`: ROI tracking settings

---

## Caching Strategy

### Cache Keys

- `ai_insights_player_{id}` - Player insights (1 hour TTL)
- `ai_insights_{id}_{type}` - Specific insight type (1 hour TTL)
- `ai_insights_freshness_{id}` - Data freshness status (5 min TTL)

### Cache Invalidation

Cache is automatically cleared when:
- New data is uploaded via `recordDataUpload()`
- Insights are refreshed via `refreshInsights()`
- Manual cache clear is triggered

### Cache Configuration

```php
'cache' => [
    'default_ttl' => 3600,        // 1 hour
    'player_insights_ttl' => 3600, // 1 hour
    'system_status_ttl' => 300,    // 5 minutes
    'enabled' => true,
],
```

---

## Scheduled Jobs

### Command: `ai:insights:refresh`

```bash
# Run scheduled refresh
php artisan ai:insights:refresh

# Force refresh all players
php artisan ai:insights:refresh --force

# Refresh specific player
php artisan ai:insights:refresh --player=1

# Dry run (preview only)
php artisan ai:insights:refresh --dry-run
```

### Schedule Configuration

In `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Run every Friday at 2:00 AM
    $schedule->command('ai:insights:refresh')
        ->weekly()
        ->fridays()
        ->at('2:00');
}
```

### Default Schedule

- **Day**: Friday
- **Time**: 2:00 AM (server time)
- **Pattern**: `weekly_friday_2am`
- **Frequency**: Weekly

### Alternative Patterns

- `daily_2am` - Daily at 2:00 AM
- `weekly_monday_6am` - Weekly Monday at 6:00 AM
- `manual` - Manual execution only

---

## Frontend Components

### Modal-Based Interface

The AI Insights system uses a modal-based interface for expandable analytics:

```blade
@include('website.players.partials.ai-insights-modal', [
    'player' => $player,
    'insights' => $groupedInsights,
    'dataFreshness' => $dataFreshness,
    'hasDynamicInsights' => true,
])
```

### Component Features

1. **Insight Cards**: Grid layout with interactive cards
2. **Modal Windows**: Detailed analysis in popup modals
3. **Confidence Indicators**: Visual confidence level badges
4. **Freshness Indicators**: Data freshness status display
5. **Responsive Design**: Works on desktop, tablet, and mobile

### Insight Types Displayed

- Strengths (⭐)
- Areas for Development (📈)
- Performance Trend (🏆)
- Playing Style (⚽)
- Predictions (🔮)

---

## Performance Metrics

### Metrics Tracked

1. **Prediction Accuracy** - How accurate predictions are
2. **User Engagement** - Views, expands, shares, exports
3. **Processing Time** - Insight generation time (ms)
4. **API Calls** - Number of API requests
5. **Cache Hit Rate** - Cache efficiency percentage
6. **Insights Generated** - Total insights created
7. **Data Freshness** - Age of data used
8. **Confidence Scores** - Average confidence level

### ROI Metrics for Investors

- Total insights generated
- Average confidence score
- Prediction accuracy
- User engagement rate
- System uptime
- Data processing time

### Accessing Metrics

```php
$metrics = app(AiInsightsService::class)->getPerformanceMetrics(30);
// Returns aggregated metrics for last 30 days
```

---

## Extensibility Guide

### Plugin Architecture

The system supports future expansion through plugin architecture:

```php
'plugins' => [
    'enabled' => true,
    'available_slots' => [
        'sentiment_analysis',
        'injury_prediction',
        'market_value_forecasting',
        'performance_comparison',
        'training_recommendations',
    ],
],
```

### Adding New Insight Types

1. Add constant to `AiInsight` model:
```php
public const TYPE_NEW_TYPE = 'new_type';
```

2. Update `getInsightTypes()` method:
```php
public static function getInsightTypes(): array
{
    return [
        // ... existing types
        self::TYPE_NEW_TYPE => 'New Type Label',
    ];
}
```

3. Add generator method in `AiInsightsGenerator`:
```php
protected function generateNewTypeInsight(Player $player, $gameStats, $dataSources): array
{
    // Generation logic
}
```

4. Add frontend component in modal template

### Custom Data Sources

1. Add type to `AiInsightsDataSource`:
```php
public const TYPE_CUSTOM = 'custom';
```

2. Register source via API:
```http
POST /api/ai-insights/players/{id}/data-sources
{
    "type": "custom",
    "name": "Custom Data Source"
}
```

3. Implement custom data retrieval in generator

---

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Register Command in Kernel

In `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('ai:insights:refresh')
        ->weekly()
        ->fridays()
        ->at('2:00');
}
```

### 4. (Optional) Run Initial Refresh

```bash
php artisan ai:insights:refresh --force
```

### 5. Verify Installation

Access the system status endpoint:
```bash
curl /api/ai-insights/system/status
```

---

## Troubleshooting

### Common Issues

1. **No insights generated**
   - Ensure player has game statistics
   - Check logs for generation errors
   - Verify data sources are registered

2. **Cache not clearing**
   - Check cache driver configuration
   - Verify cache keys match
   - Clear cache manually: `php artisan cache:clear`

3. **Scheduled job not running**
   - Verify cron is configured on server
   - Check scheduler is running: `php artisan schedule:list`
   - Review job logs in database

4. **API returning 500 errors**
   - Check Laravel logs for details
   - Verify database connection
   - Ensure required tables exist

### Logs Location

- Laravel logs: `storage/logs/laravel.log`
- Job execution logs: Database `ai_insights_jobs` table

---

## Security Considerations

- API authentication can be enabled via `AI_INSIGHTS_API_AUTH`
- Rate limiting is configured per endpoint
- Input validation is performed on all API requests
- Sensitive data is not logged

---

## Support & Maintenance

### Regular Maintenance Tasks

1. **Weekly**: Review job execution logs
2. **Monthly**: Review performance metrics and ROI
3. **Quarterly**: Review and update AI model parameters
4. **Annually**: Full system audit and upgrade planning

### Monitoring Points

- Failed job count (should be 0)
- Cache hit rate (should be > 80%)
- Average processing time (should be < 5000ms)
- User engagement metrics

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2024-01 | Initial release |

---

*This documentation was generated for AI Insights System v1.0.0*
*For questions or support, contact the development team.*
