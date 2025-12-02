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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'player', 'partner'])->default('player')->after('email');
            $table->enum('status', ['active', 'pending', 'suspended'])->default('pending')->after('user_type');
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('cascade')->after('status');
            $table->json('partner_details')->nullable()->after('player_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['player_id']);
            $table->dropColumn(['user_type', 'status', 'player_id', 'partner_details']);
        });
    }
};
