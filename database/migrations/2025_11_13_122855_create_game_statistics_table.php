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
        Schema::create('game_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->date('game_date');
            $table->string('opponent');
            $table->string('tournament')->nullable();
            $table->integer('goals_scored')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('minutes_played')->default(0);
            $table->integer('shots_on_target')->default(0);
            $table->integer('passes_completed')->default(0);
            $table->integer('tackles')->default(0);
            $table->integer('interceptions')->default(0);
            $table->integer('saves')->default(0); // For goalkeepers
            $table->integer('yellow_cards')->default(0);
            $table->integer('red_cards')->default(0);
            $table->decimal('rating', 3, 2)->nullable(); // e.g., 7.5
            $table->text('game_summary')->nullable(); // Text that can be processed by AI
            $table->boolean('ai_generated')->default(false);
            $table->json('additional_stats')->nullable(); // For flexible additional statistics
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_statistics');
    }
};
