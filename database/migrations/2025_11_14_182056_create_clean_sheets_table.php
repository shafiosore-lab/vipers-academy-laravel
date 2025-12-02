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
        Schema::create('clean_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('season', 20); // e.g., "2024/25"
            $table->string('league_name');
            $table->string('goalkeeper_name');
            $table->string('goalkeeper_image')->nullable();
            $table->string('team_name');
            $table->string('team_logo')->nullable();
            $table->integer('position')->default(0);
            $table->integer('clean_sheets')->default(0);
            $table->integer('appearances')->default(0);
            $table->decimal('clean_sheet_percentage', 5, 2)->nullable(); // percentage
            $table->integer('saves')->default(0);
            $table->integer('goals_conceded')->default(0);
            $table->decimal('save_percentage', 5, 2)->nullable(); // percentage
            $table->integer('penalties_saved')->default(0);
            $table->integer('penalties_faced')->default(0);
            $table->integer('minutes_played')->default(0);
            $table->integer('shots_faced')->default(0);
            $table->integer('shots_on_target_faced')->default(0);
            $table->decimal('shots_faced_per_game', 4, 2)->default(0);
            $table->string('nationality')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->string('dominant_hand')->default('Right'); // Right, Left
            $table->boolean('is_vipers_player')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['season', 'league_name']);
            $table->index(['season', 'clean_sheets']);
            $table->index(['season', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clean_sheets');
    }
};
