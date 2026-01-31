<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * AI Insights Data Pipeline - Version 1.0
     * This migration creates the foundation for an expandable AI-powered analytics platform.
     *
     * Table Structure:
     * - ai_insights: Core insights storage for players
     * - ai_insights_data_sources: Track data sources for each insight
     * - ai_insights_metrics: Performance metrics for AI system
     * - ai_insights_jobs: Job scheduling and execution logs
     */
    public function up(): void
    {
        // Core AI Insights Table - Stores dynamic player insights
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('insight_type', 50); // strength, development, trend, style, prediction, etc.
            $table->text('insight_content'); // The actual insight text
            $table->json('insight_data')->nullable(); // Structured data for charts/visualizations
            $table->string('confidence_level', 20)->default('medium'); // low, medium, high, very_high
            $table->decimal('confidence_score', 5, 2)->nullable(); // 0-100 numeric confidence
            $table->string('data_source', 100)->nullable(); // Source of data used
            $table->json('data_sources')->nullable(); // Array of data sources
            $table->unsignedInteger('data_points_used')->default(0); // Number of data points analyzed
            $table->timestamp('generated_at')->useCurrent(); // When insight was generated
            $table->timestamp('valid_until')->nullable(); // When insight expires
            $table->string('version', 20)->default('1.0'); // AI model version used
            $table->string('model_name', 50)->nullable(); // Name of AI model
            $table->json('model_parameters')->nullable(); // Parameters used for generation
            $table->unsignedInteger('generation_time_ms')->nullable(); // Time to generate (ms);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_manually_overridden')->default(false);
            $table->text('override_note')->nullable();
            $table->unsignedBigInteger('parent_insight_id')->nullable()->comment('For historical tracking');
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
            $table->softDeletes();

            // Indexes for efficient querying
            $table->index(['player_id', 'insight_type']);
            $table->index(['player_id', 'is_active']);
            $table->index(['generated_at']);
            $table->index(['valid_until']);
            $table->index(['confidence_level']);
        });

        // AI Insights Data Sources Table - Track data sources
        Schema::create('ai_insights_data_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('source_type', 50); // game_stats, training, biometric, scouting, external
            $table->string('source_name', 100);
            $table->string('source_identifier', 100)->nullable(); // External ID or reference
            $table->json('source_config')->nullable(); // Configuration for this source
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('data_uploaded_at')->nullable(); // When new data was uploaded
            $table->unsignedInteger('data_points_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_sync_enabled')->default(true);
            $table->string('sync_frequency', 20)->default('daily'); // hourly, daily, weekly, manual
            $table->timestamps();

            $table->index(['player_id', 'source_type']);
            $table->index(['is_active']);
            $table->index(['data_uploaded_at']);
        });

        // AI Performance Metrics Table - Track system performance
        Schema::create('ai_insights_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type', 50); // prediction_accuracy, user_engagement, processing_time
            $table->string('metric_name', 100);
            $table->decimal('metric_value', 15, 4);
            $table->string('metric_unit', 20)->nullable(); // percentage, milliseconds, count
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('set null');
            $table->string('insight_type', 50)->nullable();
            $table->json('context')->nullable(); // Additional context for the metric
            $table->timestamp('recorded_at')->useCurrent();
            $table->string('period', 20)->default('realtime'); // realtime, hourly, daily, weekly
            $table->timestamps();

            $table->index(['metric_type', 'recorded_at']);
            $table->index(['player_id', 'metric_type']);
            $table->index(['period', 'recorded_at']);
        });

        // AI Insights Jobs Table - Track scheduled job execution
        Schema::create('ai_insights_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_type', 50); // refresh, generate, sync, archive
            $table->string('job_status', 20)->default('pending'); // pending, running, completed, failed
            $table->text('job_config')->nullable(); // Configuration for this job
            $table->text('job_result')->nullable(); // Result data
            $table->text('error_message')->nullable();
            $table->json('affected_players')->nullable(); // List of player IDs affected
            $table->unsignedInteger('players_processed')->default(0);
            $table->unsignedInteger('insights_generated')->default(0);
            $table->unsignedInteger('execution_time_ms')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('next_scheduled_at')->nullable();
            $table->boolean('is_recurring')->default(true);
            $table->string('recurrence_pattern', 100)->default('weekly'); // cron expression or pattern
            $table->timestamps();

            $table->index(['job_type', 'job_status']);
            $table->index(['started_at']);
            $table->index(['next_scheduled_at']);
        });

        // AI Insights Usage Analytics Table - Track user engagement
        Schema::create('ai_insights_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('insight_type', 50);
            $table->string('user_type', 20); // visitor, registered, admin, scout
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->string('action', 50); // view, expand, share, export
            $table->integer('view_duration_ms')->nullable(); // Time spent viewing
            $table->string('device_type', 20)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->timestamps();

            $table->index(['player_id', 'insight_type']);
            $table->index(['action', 'created_at']);
            $table->index(['session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_insights_analytics');
        Schema::dropIfExists('ai_insights_jobs');
        Schema::dropIfExists('ai_insights_metrics');
        Schema::dropIfExists('ai_insights_data_sources');
        Schema::dropIfExists('ai_insights');
    }
};

