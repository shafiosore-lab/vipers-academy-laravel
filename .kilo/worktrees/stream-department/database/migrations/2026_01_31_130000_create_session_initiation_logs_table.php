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
        Schema::create('session_initiation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->timestamp('scheduled_time');
            $table->timestamp('actual_start_time')->nullable();
            $table->integer('delay_seconds')->nullable();
            $table->string('initiation_type'); // 'scheduled', 'manual', 'recovery'
            $table->unsignedBigInteger('initiated_by')->nullable();
            $table->string('status'); // 'success', 'failed', 'skipped'
            $table->text('error_message')->nullable();
            $table->integer('processing_time_ms')->nullable();
            $table->timestamp('system_time_at_execution')->nullable();
            $table->string('timezone_offset')->nullable();
            $table->timestamps();

            $table->index(['session_id', 'status']);
            $table->index(['initiation_type', 'status']);
            $table->index(['scheduled_time', 'actual_start_time']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_initiation_logs');
    }
};
