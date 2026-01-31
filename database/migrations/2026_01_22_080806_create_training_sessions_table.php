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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_type')->default('training'); // training, match
            $table->string('team_category'); // U13, U15, U17, Senior
            $table->datetime('scheduled_start_time');
            $table->datetime('actual_start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->unsignedBigInteger('started_by');
            $table->enum('status', ['scheduled', 'active', 'ended', 'cancelled'])->default('scheduled');
            $table->integer('total_duration_minutes')->nullable(); // Total session duration
            $table->integer('players_admitted')->default(0);
            $table->integer('late_arrivals')->default(0);
            $table->timestamps();

            $table->foreign('started_by')->references('id')->on('users');
            $table->index(['team_category', 'status']);
            $table->index(['scheduled_start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
