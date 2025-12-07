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
        Schema::table('player_game_stats', function (Blueprint $table) {
            // Advanced position-specific stats
            $table->integer('shots')->default(0)->after('game_summary');
            $table->integer('passes_attempted')->default(0)->after('shots');
            $table->integer('aerial_duels_won')->default(0)->after('passes_attempted');
            $table->integer('clearances')->default(0)->after('aerial_duels_won');
            $table->integer('blocks')->default(0)->after('clearances');
            $table->integer('crosses_attempted')->default(0)->after('blocks');
            $table->integer('crosses_completed')->default(0)->after('crosses_attempted');
            $table->integer('progressive_runs')->default(0)->after('crosses_completed');
            $table->integer('defensive_duels_won')->default(0)->after('progressive_runs');
            $table->integer('ball_recoveries')->default(0)->after('defensive_duels_won');
            $table->integer('progressive_passes')->default(0)->after('ball_recoveries');
            $table->integer('duels_won')->default(0)->after('progressive_passes');
            $table->decimal('expected_assists', 5, 2)->default(0)->after('duels_won');
            $table->integer('ball_progressions')->default(0)->after('expected_assists');
            $table->decimal('expected_goals', 5, 2)->default(0)->after('ball_progressions');
            $table->integer('chances_created')->default(0)->after('expected_goals');
            $table->integer('through_balls')->default(0)->after('chances_created');
            $table->integer('passes_into_final_third')->default(0)->after('through_balls');
            $table->integer('touches_in_box')->default(0)->after('passes_into_final_third');
            $table->integer('big_chances_scored')->default(0)->after('touches_in_box');
            $table->integer('big_chances_missed')->default(0)->after('big_chances_scored');
            $table->decimal('hold_up_play_success', 5, 2)->default(0)->after('big_chances_missed');
            $table->integer('chance_creation')->default(0)->after('hold_up_play_success');
            $table->integer('goals_conceded')->default(0)->after('chance_creation');
            $table->integer('high_claims')->default(0)->after('goals_conceded');
            $table->integer('punches')->default(0)->after('high_claims');
            $table->decimal('long_pass_accuracy', 5, 2)->default(0)->after('punches');
            $table->decimal('short_pass_accuracy', 5, 2)->default(0)->after('long_pass_accuracy');
            $table->integer('sweeper_actions')->default(0)->after('short_pass_accuracy');
            $table->integer('penalties_faced')->default(0)->after('sweeper_actions');
            $table->integer('penalties_saved')->default(0)->after('penalties_faced');
            $table->integer('forward_passes')->default(0)->after('penalties_saved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_game_stats', function (Blueprint $table) {
            $table->dropColumn([
                'shots', 'passes_attempted', 'aerial_duels_won', 'clearances', 'blocks',
                'crosses_attempted', 'crosses_completed', 'progressive_runs', 'defensive_duels_won',
                'ball_recoveries', 'progressive_passes', 'duels_won', 'expected_assists',
                'ball_progressions', 'expected_goals', 'chances_created', 'through_balls',
                'passes_into_final_third', 'touches_in_box', 'big_chances_scored', 'big_chances_missed',
                'hold_up_play_success', 'chance_creation', 'goals_conceded', 'high_claims', 'punches',
                'long_pass_accuracy', 'short_pass_accuracy', 'sweeper_actions', 'penalties_faced',
                'penalties_saved', 'forward_passes'
            ]);
        });
    }
};
