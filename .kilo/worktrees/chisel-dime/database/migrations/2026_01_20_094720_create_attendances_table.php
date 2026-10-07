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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->enum('session_type', ['training', 'match'])->default('training');
            $table->date('session_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->integer('total_duration_minutes')->nullable(); // auto-calculated
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade'); // coach/admin
            $table->timestamps();

            // Prevent duplicate attendance per player per session per date
            $table->unique(['player_id', 'session_type', 'session_date'], 'unique_player_session_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

