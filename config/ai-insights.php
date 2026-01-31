<?php

/**
 * AI Insights Configuration
 *
 * Configuration for the AI Insights system.
 * This file centralizes all settings for the AI-powered analytics platform.
 *
 * @package App\Config
 */

return [
    /**
     * System Version
     * Current version of the AI Insights system
     */
    'version' => '1.0.0',

    /**
     * Model Configuration
     * AI model settings for insight generation
     */
    'model' => [
        'name' => env('AI_INSIGHTS_MODEL_NAME', 'vipers-analyst-v1'),
        'version' => env('AI_INSIGHTS_MODEL_VERSION', '1.0.0'),
        'temperature' => env('AI_INSIGHTS_TEMPERATURE', 0.3),
        'max_tokens' => env('AI_INSIGHTS_MAX_TOKENS', 500),
    ],

    /**
     * Refresh Configuration
     * Settings for automated insight refresh cycles
     */
    'refresh' => [
        /**
         * Refresh Pattern
         * When to run the automated refresh job
         * Options: weekly_friday_2am, daily_2am, weekly_monday_6am, manual
         */
        'pattern' => env('AI_INSIGHTS_REFRESH_PATTERN', 'weekly_friday_2am'),

        /**
         * Default Refresh Hour
         * Hour of the day to run scheduled refresh (24-hour format)
         */
        'default_hour' => 2,

        /**
         * Default Refresh Minute
         * Minute of the hour to run scheduled refresh
         */
        'default_minute' => 0,

        /**
         * Refresh Threshold (days)
         * Number of days after which insights are considered stale
         */
        'stale_threshold_days' => 7,

        /**
         * Data Upload Threshold (days)
         * If no data uploaded within this period, trigger refresh
         */
        'data_upload_threshold_days' => 7,

        /**
         * Enable Auto Refresh
         * Set to false to disable automated refresh jobs
         */
        'auto_refresh_enabled' => env('AI_INSIGHTS_AUTO_REFRESH', true),
    ],

    /**
     * Caching Configuration
     * Settings for caching insights data
     */
    'cache' => [
        /**
         * Default Cache TTL (seconds)
         * How long to cache insights before refreshing
         */
        'default_ttl' => env('AI_INSIGHTS_CACHE_TTL', 3600), // 1 hour

        /**
         * Player Insights Cache TTL
         * Cache duration for individual player insights
         */
        'player_insights_ttl' => env('AI_INSIGHTS_PLAYER_CACHE_TTL', 3600),

        /**
         * System Status Cache TTL
         * Cache duration for system status endpoint
         */
        'system_status_ttl' => env('AI_INSIGHTS_SYSTEM_CACHE_TTL', 300),

        /**
         * Enable Cache
         * Set to false to disable caching entirely
         */
        'enabled' => env('AI_INSIGHTS_CACHE_ENABLED', true),
    ],

    /**
     * Data Source Configuration
     * Settings for data sources used in insight generation
     */
    'data_sources' => [
        /**
         * Supported Source Types
         * Types of data sources that can be integrated
         */
        'supported_types' => [
            'game_stats' => 'Game Statistics',
            'training' => 'Training Data',
            'biometric' => 'Biometric Data',
            'scouting' => 'Scouting Reports',
            'external' => 'External Data',
            'historical' => 'Historical Records',
        ],

        /**
         * Default Sync Frequency
         * Default frequency for auto-syncing data sources
         */
        'default_sync_frequency' => 'daily',

        /**
         * Max Data Points Per Source
         * Maximum number of data points to process per source
         */
        'max_data_points' => 10000,
    ],

    /**
     * Metrics Configuration
     * Settings for performance and engagement metrics
     */
    'metrics' => [
        /**
         * Enable Metrics Collection
         * Set to false to disable metrics tracking
         */
        'collection_enabled' => env('AI_INSIGHTS_METRICS_ENABLED', true),

        /**
         * Metrics Retention Period (days)
         * How long to retain historical metrics data
         */
        'retention_days' => 90,

        /**
         * Metric Types to Track
         * Types of metrics to collect
         */
        'tracked_types' => [
            'prediction_accuracy',
            'user_engagement',
            'processing_time',
            'api_calls',
            'cache_hit_rate',
            'insights_generated',
            'data_freshness',
            'confidence_score',
        ],
    ],

    /**
     * API Configuration
     * Settings for the AI Insights API
     */
    'api' => [
        /**
         * API Version
         * Current API version string
         */
        'version' => '1.0',

        /**
         * Rate Limiting
         * Number of requests per minute allowed
         */
        'rate_limit' => env('AI_INSIGHTS_RATE_LIMIT', 60),

        /**
         * Enable Authentication
         * Require API key for API access
         */
        'auth_enabled' => env('AI_INSIGHTS_API_AUTH', false),

        /**
         * API Key Header
         * Header name for API key authentication
         */
        'api_key_header' => 'X-API-Key',
    ],

    /**
     * Frontend Configuration
     * Settings for the frontend components
     */
    'frontend' => [
        /**
         * Enable Modal Mode
         * Use modal-based interface for insights display
         */
        'use_modal' => true,

        /**
         * Default Insight Types to Display
         * Which insight types to show by default
         */
        'default_types' => ['strength', 'development', 'trend', 'style'],

        /**
         * Show Confidence Indicators
         * Display confidence scores and levels
         */
        'show_confidence' => true,

        /**
         * Show Freshness Indicators
         * Display data freshness status
         */
        'show_freshness' => true,
    ],

    /**
     * Plugin Architecture Configuration
     * Settings for extensibility
     */
    'plugins' => [
        /**
         * Enable Plugin System
         * Allow loading of custom AI insight plugins
         */
        'enabled' => false,

        /**
         * Plugin Directory
         * Path to store custom plugins
         */
        'directory' => base_path('plugins/ai-insights'),

        /**
         * Available Plugin Slots
         * Types of plugins that can be integrated
         */
        'available_slots' => [
            'sentiment_analysis',
            'injury_prediction',
            'market_value_forecasting',
            'performance_comparison',
            'training_recommendations',
        ],
    ],

    /**
     * Logging Configuration
     * Settings for logging system activity
     */
    'logging' => [
        /**
         * Enable Logging
         * Set to false to disable logging
         */
        'enabled' => true,

        /**
         * Log Level
         * Minimum log level to record
         * Options: debug, info, warning, error
         */
        'level' => env('AI_INSIGHTS_LOG_LEVEL', 'info'),

        /**
         * Log Insight Generation
         * Log details of insight generation
         */
        'log_generation' => true,

        /**
         * Log API Requests
         * Log API request details
         */
        'log_api_requests' => true,
    ],

    /**
     * Investment & ROI Tracking
     * Settings for demonstrating AI investment value
     */
    'investment' => [
        /**
         * Track ROI Metrics
         * Collect metrics for ROI demonstration
         */
        'track_roi' => true,

        /**
         * ROI Report Frequency
         * How often to generate ROI reports
         */
        'report_frequency' => 'monthly',

        /**
         * Key Performance Indicators
         * KPIs to track for investors
         */
        'kpis' => [
            'total_insights_generated',
            'average_confidence_score',
            'prediction_accuracy',
            'user_engagement_rate',
            'system_uptime',
            'data_processing_time',
        ],
    ],
];
