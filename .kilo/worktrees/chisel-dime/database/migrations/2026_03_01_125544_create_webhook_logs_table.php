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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained()->onDelete('cascade');
            $table->string('event');
            $table->json('payload');
            $table->integer('response_status')->nullable();
            $table->json('response_body')->nullable();
            $table->float('response_time')->nullable();
            $table->integer('attempt')->default(1);
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['webhook_id', 'event']);
            $table->index('success');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
