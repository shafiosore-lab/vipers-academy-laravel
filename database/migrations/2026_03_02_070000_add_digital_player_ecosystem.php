<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds fields for:
     * - Player injuries and availability tracking
     * - Transfer window management
     * - Digital contracts
     */
    public function up(): void
    {
        // =============================================
        // PLAYER INJURIES AND AVAILABILITY
        // =============================================

        // Create player_injuries table
       Schema::create('player_injuries', function (Blueprint $table) {
    $table->id();

    $table->foreignId('player_id')
        ->constrained()
        ->onDelete('cascade');

    // Injury details
    $table->string('injury_type');
    $table->string('body_part');
    $table->string('severity');
    $table->text('description')->nullable();

    // Dates
    $table->date('injury_date');
    $table->date('expected_recovery_date')->nullable();
    $table->date('actual_recovery_date')->nullable();

    // Status
    $table->string('status');
    $table->text('treatment_notes')->nullable();
    $table->string('treating_physician')->nullable();

    // Tracking
    $table->foreignId('reported_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('updated_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();

    // Optimized indexes
    $table->index(
        ['player_id', 'status'],
        'pi_player_status_idx'
    );

    $table->index(
        ['expected_recovery_date', 'status'],
        'pi_recovery_status_idx'
    );
});
        // Create player_availability table
        Schema::create('player_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');

            // Availability details
            $table->date('availability_date');
            $table->string('status'); // available, unavailable, tentative, injury
            $table->string('reason')->nullable(); // match, training, personal, injury, suspension
            $table->text('notes')->nullable();

            // Tracking
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['player_id', 'availability_date']);
            $table->index(
                ['organization_id', 'availability_date', 'status'],
                'pa_org_date_status_idx'
            );
            $table->unique(['player_id', 'availability_date']);
        });

        // Add availability tracking to players
        Schema::table('players', function (Blueprint $table) {
            $table->string('availability_status')->default('available')->after('status');
            $table->text('availability_notes')->nullable()->after('availability_status');
            $table->date('last_availability_update')->nullable()->after('availability_notes');
        });

        // =============================================
        // TRANSFER WINDOW MANAGEMENT
        // =============================================

        // Create transfer_windows table
        Schema::create('transfer_windows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('window_type'); // transfer, loan, emergency
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_loan')->default(false);
            $table->boolean('allow_emergency')->default(false);
            $table->text('rules')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['organization_id', 'is_active']);
            $table->index(['tournament_id']);
            $table->index(['start_date', 'end_date']);
        });

        // Create player_transfers table
        Schema::create('player_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('transfer_window_id')->constrained()->onDelete('cascade');

            // Transfer details
            $table->foreignId('from_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('to_team_id')->constrained('teams')->onDelete('cascade');
            $table->string('transfer_type'); // permanent, loan, free

            // Financial
            $table->decimal('transfer_fee', 12, 2)->default(0);
            $table->string('fee_type')->nullable(); // fixed, negotiable, free
            $table->text('financial_notes')->nullable();

            // Contract
            $table->integer('contract_duration_months')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('signing_bonus', 10, 2)->nullable();

            // Status and workflow
            $table->string('status'); // pending, approved, rejected, cancelled, completed
            $table->text('rejection_reason')->nullable();

            // Dates
            $table->date('requested_date');
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();

            // Approval chain
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // Documentation
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['player_id', 'status']);
            $table->index(['transfer_window_id', 'status']);
            $table->index(['from_team_id']);
            $table->index(['to_team_id']);
        });

        // Add transfer-related fields to players
        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('current_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->date('joined_date')->nullable()->after('current_team_id');
            $table->date('contract_end_date')->nullable()->after('joined_date');
            $table->string('contract_status')->default('active')->after('contract_end_date'); // active, expired, terminated
        });

        // =============================================
        // DIGITAL CONTRACTS
        // =============================================

        // Create player_contracts table
        Schema::create('player_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();

            // Contract details
            $table->string('contract_type'); // permanent, loan, youth, amateur, professional
            $table->string('contract_number')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_months');

            // Financial terms
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('signing_bonus', 10, 2)->nullable();
            $table->decimal('release_clause', 12, 2)->nullable();
            $table->string('payment_frequency')->default('monthly'); // weekly, monthly, annually
            $table->json('salary_breaks')->nullable(); // Year-by-year salary breakdown

            // Performance bonuses
            $table->json('performance_bonuses')->nullable(); // Goal bonuses, appearance fees, etc.
            $table->decimal('total_contract_value', 14, 2)->nullable();

            // Status
            $table->string('status'); // draft, pending_signature, active, expired, terminated, renewed
            $table->text('termination_reason')->nullable();
            $table->date('termination_date')->nullable();

            // Digital signature
            $table->boolean('is_digitally_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_token')->nullable()->unique();
            $table->json('signatures')->nullable(); // Store signature data

            // Legal
            $table->text('special_clauses')->nullable();
            $table->text('notes')->nullable();

            // Documents
            $table->string('document_path')->nullable();
            $table->json('attachments')->nullable();

            // Approval workflow
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['player_id', 'status']);
            $table->index(['organization_id', 'status']);
            $table->index(['team_id', 'status']);
            $table->index(['end_date', 'status']);
            $table->index('contract_number');
        });

        // Create contract_renewals table
        Schema::create('contract_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_contract_id')->constrained('player_contracts')->onDelete('cascade');
            $table->foreignId('previous_contract_id')->nullable()->constrained('player_contracts')->nullOnDelete();
            $table->foreignId('new_contract_id')->nullable()->constrained('player_contracts')->nullOnDelete();

            $table->decimal('salary_increase', 10, 2)->nullable();
            $table->decimal('salary_increase_percentage', 5, 2)->nullable();
            $table->string('renewal_type'); // automatic, negotiated, upgrade
            $table->text('notes')->nullable();

            $table->timestamps();
        });

        // Create contract_amendments table (for contract modifications)
        Schema::create('contract_amendments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_contract_id')->constrained('player_contracts')->onDelete('cascade');

            $table->string('amendment_type'); // salary_change, duration_change, clause_addition, termination
            $table->text('description');
            $table->text('previous_value')->nullable();
            $table->text('new_value')->nullable();

            $table->string('status'); // pending, approved, rejected
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['player_contract_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Contract amendments
        Schema::dropIfExists('contract_amendments');

        // Contract renewals
        Schema::dropIfExists('contract_renewals');

        // Player contracts
        Schema::dropIfExists('player_contracts');

        // Remove contract fields from players
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['current_team_id', 'joined_date', 'contract_end_date', 'contract_status']);
        });

        // Player transfers
        Schema::dropIfExists('player_transfers');

        // Transfer windows
        Schema::dropIfExists('transfer_windows');

        // Player availability
        Schema::dropIfExists('player_availability');

        // Remove availability fields from players
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['availability_status', 'availability_notes', 'last_availability_update']);
        });

        // Player injuries
        Schema::dropIfExists('player_injuries');
    }
};
