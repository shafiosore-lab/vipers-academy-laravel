<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds match type, format configuration, timezone, and cancellation tracking to matches
     */
    public function up(): void
    {
        Schema::table('tournament_matches', function (Blueprint $table) {
            // Match type classification
            $table->enum('match_type', [
                'tournament',
                'league',
                'friendly',
                'knockout',
                'group_stage',
                'exhibition'
            ])->default('tournament')->after('tournament_id');

            // Pool/Group assignment
            $table->foreignId('pool_id')->nullable()->constrained('tournament_pools')->nullOnDelete()->after('match_type');

            // Venue from tournament venues
            $table->foreignId('venue_id')->nullable()->constrained('tournament_venues')->nullOnDelete()->after('pool_id');

            // Timezone support
            $table->string('timezone')->default('UTC')->after('kickoff_time');

            // Match format specifications (JSON)
            $table->json('match_format')->nullable()->after('status'); // duration, periods, overtime rules
            $table->json('scoring_rules')->nullable()->after('match_format'); // point systems, tiebreakers

            // Additional scheduling
            $table->integer('scheduled_day')->nullable()->after('round'); // Day number in tournament
            $table->time('scheduled_time')->nullable()->after('scheduled_day');

            // Cancellation tracking
            $table->string('cancellation_reason')->nullable()->after('notes');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete()->after('cancellation_reason');
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');

            // Rescheduling
            $table->timestamp('original_kickoff_time')->nullable()->after('cancelled_at');

            // Status timestamps
            $table->timestamp('started_at')->nullable()->after('original_kickoff_time');
            $table->timestamp('completed_at')->nullable()->after('started_at');

            // Home/Away determination
            $table->enum('home_away', ['home', 'away', 'neutral'])->default('neutral')->after('away_team_id');

            // Leg info for two-legged ties
            $table->integer('leg_number')->nullable()->after('home_away'); // 1, 2 for knockout legs
            $table->foreignId('aggregate_match_id')->nullable()->constrained('tournament_matches')->nullOnDelete()->after('leg_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('aggregate_match_id');
            $table->dropColumn('leg_number');
            $table->dropColumn('home_away');
            $table->dropColumn('completed_at');
            $table->dropColumn('started_at');
            $table->dropColumn('original_kickoff_time');
            $table->dropConstrainedForeignId('cancelled_by');
            $table->dropColumn('cancelled_at');
            $table->dropColumn('cancellation_reason');
            $table->dropColumn('scheduled_time');
            $table->dropColumn('scheduled_day');
            $table->dropColumn('scoring_rules');
            $table->dropColumn('match_format');
            $table->dropColumn('timezone');
            $table->dropConstrainedForeignId('venue_id');
            $table->dropConstrainedForeignId('pool_id');
            $table->dropColumn('match_type');
        });
    }
};
