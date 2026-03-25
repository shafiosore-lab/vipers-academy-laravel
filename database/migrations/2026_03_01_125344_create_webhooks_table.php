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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->json('events');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('secret');
            $table->boolean('enabled')->default(true);
            $table->integer('retry_attempts')->default(3);
            $table->integer('timeout')->default(10);
            $table->json('headers')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'enabled']);
            $table->index('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
