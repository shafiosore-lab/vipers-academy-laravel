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
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->nullable()->after('id');
            $table->integer('trained_minutes')->nullable()->after('total_duration_minutes');
            $table->integer('missed_minutes')->default(0)->after('trained_minutes');
            $table->enum('lateness_category', ['on_time', 'late', 'very_late'])->default('on_time')->after('missed_minutes');

            $table->foreign('session_id')->references('id')->on('training_sessions')->onDelete('cascade');
            $table->dropUnique('unique_player_session_date');
            $table->unique(['player_id', 'session_id'], 'unique_player_session');
            $table->index(['session_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn(['session_id', 'trained_minutes', 'missed_minutes', 'lateness_category']);
        });
    }
};
