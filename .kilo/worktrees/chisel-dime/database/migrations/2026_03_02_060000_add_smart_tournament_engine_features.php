<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds fields for:
     * - Custom format builder (tournaments)
     * - ELO ratings (tournament_teams)
     * - Referee allocation (new table)
     * - Venue location coordinates (tournament_venues)
     */
    public function up(): void
    {
        // Add custom format config to tournaments
        Schema::table('tournaments', function (Blueprint $table) {
            $table->json('custom_format_config')->nullable()->after('competition_format');
            $table->string('custom_format_name')->nullable()->after('custom_format_config');
        });

        // Add ELO rating and performance data to tournament_teams
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->integer('elo_rating')->default(1500)->after('seed_number');
            $table->integer('elo_matches')->default(0)->after('elo_rating');
            $table->integer('wins')->default(0)->after('elo_matches');
            $table->integer('draws')->default(0)->after('wins');
            $table->integer('losses')->default(0)->after('draws');
            $table->integer('goals_for')->default(0)->after('losses');
            $table->integer('goals_against')->default(0)->after('goals_for');
            $table->integer('last_elo_change')->default(0)->after('goals_against');
            $table->timestamp('last_match_date')->nullable()->after('last_elo_change');
        });

        // Add location coordinates to tournament_venues
        Schema::table('tournament_venues', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('country');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('timezone')->default('UTC')->after('longitude');
        });

        // Create tournament_referees table
        Schema::create('tournament_referees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('license_number')->nullable();
            $table->string('certification_level')->nullable(); // FIFA, National, Local, etc.
            $table->boolean('is_active')->default(true);
            $table->integer('matches officiated')->default(0);
            $table->decimal('rating', 3, 2)->default(5.00); // Performance rating
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tournament_id', 'is_active']);
            $table->index(['tournament_id', 'certification_level']);
        });

        // Add referee_id to tournament_matches
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->foreignId('referee_id')->nullable()->constrained('tournament_referees')->nullOnDelete();
            $table->foreignId('assistant_referee_1_id')->nullable()->constrained('tournament_referees')->nullOnDelete();
            $table->foreignId('assistant_referee_2_id')->nullable()->constrained('tournament_referees')->nullOnDelete();
            $table->foreignId('fourth_official_id')->nullable()->constrained('tournament_referees')->nullOnDelete();
        });

        // Add match_officials_json to store complete official assignment
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->json('match_officials')->nullable()->after('fourth_official_id');
            $table->json('custom_bracket_config')->nullable()->after('match_officials'); // For drag-drop bracket builder
            $table->integer('bracket_position')->nullable()->after('custom_bracket_config'); // Position in bracket
            $table->foreignId('next_match_id')->nullable()->constrained('tournament_matches')->nullOnDelete(); // For bracket progression
            $table->foreignId('loser_next_match_id')->nullable()->constrained('tournament_matches')->nullOnDelete(); // For double elimination
        });

        // Add hybrid_stage_config to tournaments for group+knockout configurations
        Schema::table('tournaments', function (Blueprint $table) {
            $table->json('hybrid_stage_config')->nullable()->after('custom_format_name');
            $table->integer('teams_from_group')->default(2)->after('hybrid_stage_config'); // How many teams advance from each group
            $table->boolean('include_third_place')->default(false)->after('teams_from_group');
            $table->boolean('include_consolation')->default(false)->after('include_third_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop referee-related columns
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropForeign(['referee_id']);
            $table->dropForeign(['assistant_referee_1_id']);
            $table->dropForeign(['assistant_referee_2_id']);
            $table->dropForeign(['fourth_official_id']);
            $table->dropForeign(['next_match_id']);
            $table->dropForeign(['loser_next_match_id']);
            $table->dropColumn([
                'referee_id',
                'assistant_referee_1_id',
                'assistant_referee_2_id',
                'fourth_official_id',
                'match_officials',
                'custom_bracket_config',
                'bracket_position',
                'next_match_id',
                'loser_next_match_id',
            ]);
        });

        Schema::dropIfExists('tournament_referees');

        // Remove venue location data
        Schema::table('tournament_venues', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'timezone']);
        });

        // Remove team ELO and performance data
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->dropColumn([
                'elo_rating',
                'elo_matches',
                'wins',
                'draws',
                'losses',
                'goals_for',
                'goals_against',
                'last_elo_change',
                'last_match_date',
            ]);
        });

        // Remove custom format fields
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn([
                'custom_format_config',
                'custom_format_name',
                'hybrid_stage_config',
                'teams_from_group',
                'include_third_place',
                'include_consolation',
            ]);
        });
    }
};
