<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds pool assignment to tournament teams
     */
    public function up(): void
    {
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->foreignId('pool_id')->nullable()->constrained('tournament_pools')->nullOnDelete()->after('tournament_id');
            $table->integer('seed_number')->nullable()->after('pool_id'); // For seeding within pool
            $table->integer('pool_position')->nullable()->after('seed_number'); // Position within pool
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pool_id');
            $table->dropColumn('seed_number');
            $table->dropColumn('pool_position');
        });
    }
};
