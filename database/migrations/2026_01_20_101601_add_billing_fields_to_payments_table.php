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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('guardian_id')->nullable()->constrained('guardians')->onDelete('set null')->after('user_id');
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('set null')->after('guardian_id');
            $table->string('month_applied_to', 7)->nullable()->after('due_date'); // YYYY-MM format
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['guardian_id']);
            $table->dropForeign(['player_id']);
            $table->dropColumn(['guardian_id', 'player_id', 'month_applied_to']);
        });
    }
};

