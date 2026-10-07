<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table stores pools/divisions within tournaments for team分组管理
     */
    public function up(): void
    {
        Schema::create('tournament_pools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Pool A, Pool B, Division 1, etc.
            $table->string('description')->nullable();
            $table->integer('position')->default(0); // For ordering pools
            $table->string('seed_method')->default('manual'); // manual, random, seeding, performance
            $table->timestamps();

            $table->unique(['tournament_id', 'name']);
            $table->index(['tournament_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_pools');
    }
};
