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
        Schema::table('tournaments', function (Blueprint $table) {
            $table->enum('competition_format', [
                'league',
                'league_cup',
                'knockout',
                'knockout_plus',
                'groups_knockout',
                'double_elimination',
                'round_robin',
            ])->nullable()->after('max_teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('competition_format');
        });
    }
};
