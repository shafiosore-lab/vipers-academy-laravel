<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds fields for:
     * - Age fraud detection
     * - Disciplinary management
     * - Appeals and protests
     */
    public function up(): void
    {
        // =============================================
        // AGE FRAUD DETECTION
        // =============================================

        // Create player_age_verifications table
        Schema::create('player_age_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');

            // Verification details
            $table->date('verification_date');
            $table->string('verification_method'); // birth_certificate, national_id, passport, medical, biometric
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();

            // Age assessment
            $table->date('assessed_date_of_birth')->nullable();
            $table->integer('assessed_age')->nullable();
            $table->integer('declared_age')->nullable();
            $table->integer('age_difference')->nullable(); // Difference in years

            // Verification result
            $table->string('status'); // pending, verified, flagged, suspected_fraud, cleared
            $table->text('findings')->nullable();
            $table->text('notes')->nullable();

            // Medical/biometric data (optional)
            $table->json('medical_assessment')->nullable(); // Doctor's assessment
            $table->json('biometric_data')->nullable(); // For future use

            // Follow-up
            $table->boolean('requires_follow_up')->default(false);
            $table->date('follow_up_date')->nullable();

            // Investigator
            $table->foreignId('investigated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['player_id', 'status']);
            $table->index(['organization_id', 'status']);
            $table->index(['verification_date', 'status']);
        });

        // Create age_alert_rules table
        Schema::create('age_alert_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('category'); // under-10, under-12, under-14, etc.
            $table->integer('min_age');
            $table->integer('max_age');
            $table->integer('alert_threshold_days')->default(30); // Days before age cutoff to alert
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_flag')->default(false); // Auto-flag players near cutoff
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['organization_id', 'is_active', 'category']);
        });

        // Add age verification status to players
            Schema::table('players', function (Blueprint $table) {

            if (!Schema::hasColumn('players', 'age_verification_status')) {
                $table->enum(
                    'age_verification_status',
                    ['unverified', 'pending', 'verified', 'flagged']
                )->default('unverified');
            }

            if (!Schema::hasColumn('players', 'last_age_verification_date')) {
                $table->date('last_age_verification_date')->nullable();
            }

            if (!Schema::hasColumn('players', 'age_discrepancy_count')) {
                $table->unsignedTinyInteger('age_discrepancy_count')->default(0);
            }

        });

        // =============================================
        // DISCIPLINARY MANAGEMENT
        // =============================================

        // Create disciplinary_cases table
        Schema::create('disciplinary_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tournament_id')->nullable()->constrained()->nullOnDelete();

            // Case details
            $table->string('case_number')->unique();
            $table->string('incident_type'); // violent_conduct, dissent, abusive_language, spitting, assault, etc.
            $table->text('description');
            $table->dateTime('incident_date');
            $table->string('incident_location')->nullable();

            // Match-related (if applicable)
            $table->foreignId('match_id')->nullable()->constrained('tournament_matches')->nullOnDelete();

            // Evidence
            $table->json('evidence')->nullable(); // Links to documents, videos
            $table->json('witness_statements')->nullable();

            // Initial card shown
            $table->string('card_shown')->nullable(); // yellow, red, none

            // Decision
            $table->string('offense_type')->nullable(); // minor, moderate, serious, gross_misconduct
            $table->string('status'); // open, under_review, pending_decision, closed
            $table->string('decision')->nullable(); // warning, fine, suspension, ban, cleared
            $table->integer('suspension_matches')->nullable(); // Number of matches
            $table->integer('suspension_days')->nullable(); // Number of days
            $table->decimal('fine_amount', 10, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('end_date')->nullable();

            // Notes
            $table->text('decision_reason')->nullable();
            $table->text('notes')->nullable();

            // Created by
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('decided_at')->nullable();

            $table->timestamps();

            $table->index(['player_id', 'status']);
            $table->index(['organization_id', 'status']);
            $table->index(['tournament_id']);
            $table->index(['case_number']);
        });

        // Create player_suspensions table (active suspensions tracking)
        Schema::create('player_suspensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('disciplinary_case_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tournament_id')->nullable()->constrained()->nullOnDelete();

            // Suspension details
            $table->string('suspension_type'); // match_ban, temporary_suspension, permanent_ban
            $table->integer('matches_to_serve')->default(0);
            $table->integer('matches_served')->default(0);
            $table->integer('days_to_serve')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->nullable();

            // Status
            $table->string('status'); // active, served, expired, breached

            // Notes
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['player_id', 'status']);
            $table->index(['organization_id', 'status']);
            $table->index(['tournament_id', 'status']);
        });

        // Add card tracking to tournament matches
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->integer('home_yellow_cards')->default(0)->after('away_score');
            $table->integer('away_yellow_cards')->default(0)->after('home_yellow_cards');
            $table->integer('home_red_cards')->default(0)->after('away_yellow_cards');
            $table->integer('away_red_cards')->default(0)->after('home_red_cards');
            $table->json('card_details')->nullable()->after('away_red_cards'); // Detailed card info
        });

        // =============================================
        // APPEALS AND PROTESTS
        // =============================================

        // Create appeals table
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplinary_case_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');

            // Appeal details
            $table->string('appeal_number')->unique();
            $table->text('grounds'); // Reasons for appeal
            $table->text('evidence')->nullable();
            $table->json('supporting_documents')->nullable();

            // Status
            $table->string('status'); // pending, under_review, accepted, rejected, withdrawn
            $table->string('outcome')->nullable(); // upheld, dismissed, modified
            $table->text('outcome_reason')->nullable();

            // Original decision
            $table->string('original_decision')->nullable();
            $table->text('original_reason')->nullable();

            // Modified decision (if changed)
            $table->string('modified_decision')->nullable();
            $table->integer('modified_suspension_matches')->nullable();
            $table->integer('modified_suspension_days')->nullable();
            $table->decimal('modified_fine_amount', 10, 2)->nullable();

            // Panel
            $table->foreignId('heard_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('heard_at')->nullable();
            $table->date('decision_date')->nullable();

            // Timestamps
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['player_id', 'status']);
            $table->index(['disciplinary_case_id', 'status']);
            $table->index(['appeal_number']);
        });

        // Create protests table (for match-related protests)
        Schema::create('protests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('tournament_matches')->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');

            // Protest details
            $table->string('protest_number')->unique();
            $table->string('protest_type'); // referee_decision, eligibility, venue, schedule, other
            $table->text('description');
            $table->text('grounds');
            $table->json('evidence')->nullable();

            // Status
            $table->string('status'); // pending, under_review, upheld, rejected, withdrawn
            $table->string('outcome')->nullable();
            $table->text('outcome_reason')->nullable();

            // Resolution
            $table->string('resolution')->nullable(); // match_voided, result_standing, replay_ordered, protest_dismissed
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('resolved_at')->nullable();

            // Timestamps
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['match_id', 'status']);
            $table->index(['team_id', 'status']);
            $table->index(['tournament_id', 'status']);
            $table->index(['protest_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop protests
        Schema::dropIfExists('protests');

        // Drop appeals
        Schema::dropIfExists('appeals');

        // Remove card tracking from matches
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropColumn(['home_yellow_cards', 'away_yellow_cards', 'home_red_cards', 'away_red_cards', 'card_details']);
        });

        // Drop suspensions
        Schema::dropIfExists('player_suspensions');

        // Drop disciplinary cases
        Schema::dropIfExists('disciplinary_cases');

        // Remove age verification from players
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['age_verification_status', 'last_age_verification_date', 'age_discrepancy_count']);
        });

        // Drop age alert rules
        Schema::dropIfExists('age_alert_rules');

        // Drop age verifications
        Schema::dropIfExists('player_age_verifications');
    }
};
