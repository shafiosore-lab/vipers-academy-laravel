<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds multi-tenant support and additional fields
     */
    public function up(): void
    {
        // 1. Create teams table first (for team-based expenses)
        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
                $table->string('name');
                $table->string('age_group')->nullable(); // U10, U12, U15, Senior, etc.
                $table->string('category')->nullable(); // Academy, Youth, Senior
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index('organization_id');
            });
        }

        // 2. Create events table if not exists (for match/tournament expenses)
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
                $table->string('name');
                $table->enum('type', ['match', 'tournament', 'training_camp', 'social', 'other'])->default('match');
                $table->date('event_date');
                $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
                $table->foreignId('home_team_id')->nullable()->constrained('teams')->nullOnDelete();
                $table->foreignId('away_team_id')->nullable()->constrained('teams')->nullOnDelete();
                $table->string('venue')->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index('organization_id');
                $table->index('event_date');
            });
        }

        // 3. Add organization_id to expense_categories (if not exists)
        if (!Schema::hasColumn('expense_categories', 'organization_id')) {
            Schema::table('expense_categories', function (Blueprint $table) {
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete()->after('created_by');
            });
        }
        if (!Schema::hasColumn('expense_categories', 'group_name')) {
            Schema::table('expense_categories', function (Blueprint $table) {
                $table->string('group_name')->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('expense_categories', 'is_system')) {
            Schema::table('expense_categories', function (Blueprint $table) {
                $table->boolean('is_system')->default(false)->after('is_active');
            });
        }

        // Add unique constraint if not exists
        try {
            Schema::table('expense_categories', function (Blueprint $table) {
                $table->unique(['slug', 'organization_id'], 'expense_cat_slug_org_unique');
            });
        } catch (\Exception $e) {
            // Constraint may already exist
        }

        // 4. Add organization_id to budget_plans (if not exists)
        if (!Schema::hasColumn('budget_plans', 'organization_id')) {
            Schema::table('budget_plans', function (Blueprint $table) {
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete()->after('approved_by');
            });
        }
        if (!Schema::hasColumn('budget_plans', 'season_label')) {
            Schema::table('budget_plans', function (Blueprint $table) {
                $table->string('season_label')->nullable()->after('month');
            });
        }
        if (!Schema::hasColumn('budget_plans', 'start_date')) {
            Schema::table('budget_plans', function (Blueprint $table) {
                $table->date('start_date')->nullable()->after('season_label');
            });
        }
        if (!Schema::hasColumn('budget_plans', 'end_date')) {
            Schema::table('budget_plans', function (Blueprint $table) {
                $table->date('end_date')->nullable()->after('start_date');
            });
        }

        // Update enum type
        DB::statement("ALTER TABLE budget_plans MODIFY COLUMN type ENUM('monthly', 'yearly', 'seasonal')");

        // 5. Add organization_id to budget_items (if not exists)
        if (!Schema::hasColumn('budget_items', 'organization_id')) {
            Schema::table('budget_items', function (Blueprint $table) {
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete()->after('budget_plan_id');
            });
        }

        // 6. Add organization_id, team_id, event_id to expenses (if not exists)
        if (!Schema::hasColumn('expenses', 'organization_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete()->after('approved_by');
            });
        }
        if (!Schema::hasColumn('expenses', 'team_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete()->after('organization_id');
            });
        }
        if (!Schema::hasColumn('expenses', 'event_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete()->after('team_id');
            });
        }
        if (!Schema::hasColumn('expenses', 'player_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('player_id')->nullable()->constrained('players')->nullOnDelete()->after('event_id');
            });
        }
        if (!Schema::hasColumn('expenses', 'attachment_path')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->string('attachment_path')->nullable()->after('notes');
            });
        }
        if (!Schema::hasColumn('expenses', 'approved_at')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            });
        }
        if (!Schema::hasColumn('expenses', 'paid_at')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->timestamp('paid_at')->nullable()->after('approved_at');
            });
        }

        // Add indexes
        try {
            Schema::table('expenses', function (Blueprint $table) {
                $table->index(['organization_id', 'status']);
                $table->index(['organization_id', 'expense_date']);
            });
        } catch (\Exception $e) {
            // Indexes may already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys and columns in reverse order
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['organization_id', 'status']);
            $table->dropIndex(['organization_id', 'expense_date']);
            $table->dropColumn([
                'organization_id', 'team_id', 'event_id', 'player_id',
                'attachment_path', 'approved_at', 'paid_at'
            ]);
        });

        Schema::table('budget_items', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('budget_plans', function (Blueprint $table) {
            $table->dropColumn([
                'organization_id', 'season_label', 'start_date', 'end_date'
            ]);
            // Revert enum
            DB::statement("ALTER TABLE budget_plans MODIFY COLUMN type ENUM('monthly', 'yearly')");
        });

        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropColumn(['organization_id', 'group_name', 'is_system']);
        });

        // Don't drop teams and events tables - they might be used elsewhere
    }
};
