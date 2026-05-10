<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | PLAYER INJURIES
        |--------------------------------------------------------------------------
        */

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

            // Indexes
            $table->index(
                ['player_id', 'status'],
                'pi_player_status_idx'
            );

            $table->index(
                ['expected_recovery_date', 'status'],
                'pi_recovery_status_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | PLAYER AVAILABILITY
        |--------------------------------------------------------------------------
        */

        Schema::create('player_availability', function (Blueprint $table) {
            $table->id();

            $table->foreignId('player_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('organization_id')
                ->constrained()
                ->onDelete('cascade');

            // Availability details
            $table->date('availability_date');
            $table->string('status');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();

            // Tracking
            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Optimized indexes
            $table->index(
                ['player_id', 'availability_date'],
                'pa_player_date_idx'
            );

            $table->index(
                ['organization_id', 'availability_date', 'status'],
                'pa_org_date_status_idx'
            );

            $table->unique(
                ['player_id', 'availability_date'],
                'pa_player_date_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | ADD AVAILABILITY FIELDS TO PLAYERS
        |--------------------------------------------------------------------------
        */

        Schema::table('players', function (Blueprint $table) {

            if (!Schema::hasColumn('players', 'availability_status')) {
                $table->string('availability_status')
                    ->default('available')
                    ->after('status');
            }

            if (!Schema::hasColumn('players', 'availability_notes')) {
                $table->text('availability_notes')
                    ->nullable()
                    ->after('availability_status');
            }

            if (!Schema::hasColumn('players', 'last_availability_update')) {
                $table->date('last_availability_update')
                    ->nullable()
                    ->after('availability_notes');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | TRANSFER WINDOWS
        |--------------------------------------------------------------------------
        */

        Schema::create('transfer_windows', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('tournament_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('window_type');

            $table->date('start_date');
            $table->date('end_date');

            $table->boolean('is_active')->default(true);
            $table->boolean('allow_loan')->default(false);
            $table->boolean('allow_emergency')->default(false);

            $table->text('rules')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(
                ['organization_id', 'is_active'],
                'tw_org_active_idx'
            );

            $table->index(
                ['start_date', 'end_date'],
                'tw_dates_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | PLAYER TRANSFERS
        |--------------------------------------------------------------------------
        */

        Schema::create('player_transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('player_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('transfer_window_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('from_team_id')
                ->constrained('teams')
                ->onDelete('cascade');

            $table->foreignId('to_team_id')
                ->constrained('teams')
                ->onDelete('cascade');

            // Transfer details
            $table->string('transfer_type');

            // Financial
            $table->decimal('transfer_fee', 12, 2)->default(0);
            $table->string('fee_type')->nullable();
            $table->text('financial_notes')->nullable();

            // Contract
            $table->integer('contract_duration_months')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('signing_bonus', 10, 2)->nullable();

            // Status
            $table->string('status');
            $table->text('rejection_reason')->nullable();

            // Dates
            $table->date('requested_date');
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();

            // Approval
            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            // Documentation
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(
                ['player_id', 'status'],
                'pt_player_status_idx'
            );

            $table->index(
                ['transfer_window_id', 'status'],
                'pt_window_status_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | ADD TRANSFER FIELDS TO PLAYERS
        |--------------------------------------------------------------------------
        */

        Schema::table('players', function (Blueprint $table) {

            if (!Schema::hasColumn('players', 'current_team_id')) {
                $table->foreignId('current_team_id')
                    ->nullable()
                    ->constrained('teams')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('players', 'joined_date')) {
                $table->date('joined_date')
                    ->nullable()
                    ->after('current_team_id');
            }

            if (!Schema::hasColumn('players', 'contract_end_date')) {
                $table->date('contract_end_date')
                    ->nullable()
                    ->after('joined_date');
            }

            if (!Schema::hasColumn('players', 'contract_status')) {
                $table->string('contract_status')
                    ->default('active')
                    ->after('contract_end_date');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | PLAYER CONTRACTS
        |--------------------------------------------------------------------------
        */

        Schema::create('player_contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('player_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('organization_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('team_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Contract details
            $table->string('contract_type');
            $table->string('contract_number')->unique();

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('duration_months');

            // Financial terms
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('signing_bonus', 10, 2)->nullable();
            $table->decimal('release_clause', 12, 2)->nullable();

            $table->string('payment_frequency')
                ->default('monthly');

            $table->json('salary_breaks')->nullable();

            // Bonuses
            $table->json('performance_bonuses')->nullable();

            $table->decimal('total_contract_value', 14, 2)
                ->nullable();

            // Status
            $table->string('status');

            $table->text('termination_reason')->nullable();
            $table->date('termination_date')->nullable();

            // Digital signature
            $table->boolean('is_digitally_signed')
                ->default(false);

            $table->timestamp('signed_at')->nullable();

            $table->string('signature_token')
                ->nullable()
                ->unique();

            $table->json('signatures')->nullable();

            // Legal
            $table->text('special_clauses')->nullable();
            $table->text('notes')->nullable();

            // Documents
            $table->string('document_path')->nullable();
            $table->json('attachments')->nullable();

            // Approval
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(
                ['player_id', 'status'],
                'pc_player_status_idx'
            );

            $table->index(
                ['organization_id', 'status'],
                'pc_org_status_idx'
            );

            $table->index(
                ['team_id', 'status'],
                'pc_team_status_idx'
            );

            $table->index(
                ['end_date', 'status'],
                'pc_end_status_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | CONTRACT RENEWALS
        |--------------------------------------------------------------------------
        */

        Schema::create('contract_renewals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('player_contract_id')
                ->constrained('player_contracts')
                ->onDelete('cascade');

            $table->foreignId('previous_contract_id')
                ->nullable()
                ->constrained('player_contracts')
                ->nullOnDelete();

            $table->foreignId('new_contract_id')
                ->nullable()
                ->constrained('player_contracts')
                ->nullOnDelete();

            $table->decimal('salary_increase', 10, 2)
                ->nullable();

            $table->decimal('salary_increase_percentage', 5, 2)
                ->nullable();

            $table->string('renewal_type');

            $table->text('notes')->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | CONTRACT AMENDMENTS
        |--------------------------------------------------------------------------
        */

        Schema::create('contract_amendments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('player_contract_id')
                ->constrained('player_contracts')
                ->onDelete('cascade');

            $table->string('amendment_type');

            $table->text('description');

            $table->text('previous_value')->nullable();
            $table->text('new_value')->nullable();

            $table->string('status');

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(
                ['player_contract_id', 'status'],
                'ca_contract_status_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_amendments');
        Schema::dropIfExists('contract_renewals');
        Schema::dropIfExists('player_contracts');
        Schema::dropIfExists('player_transfers');
        Schema::dropIfExists('transfer_windows');
        Schema::dropIfExists('player_availability');
        Schema::dropIfExists('player_injuries');

        Schema::table('players', function (Blueprint $table) {

            $columns = [
                'availability_status',
                'availability_notes',
                'last_availability_update',
                'current_team_id',
                'joined_date',
                'contract_end_date',
                'contract_status'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('players', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
