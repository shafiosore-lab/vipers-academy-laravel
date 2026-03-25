<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds 'type' field to organizations for tournament support
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->enum('type', ['club', 'academy', 'tournament'])->default('club')->after('slug');
            $table->boolean('is_tournament_active')->default(false)->after('type');
            $table->dateTime('tournament_registration_deadline')->nullable()->after('is_tournament_active');
            $table->integer('tournament_squad_limit')->default(25)->after('tournament_registration_deadline');
            $table->integer('tournament_min_players')->default(11)->after('tournament_squad_limit');
            $table->text('tournament_rules')->nullable()->after('tournament_min_players');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'is_tournament_active',
                'tournament_registration_deadline',
                'tournament_squad_limit',
                'tournament_min_players',
                'tournament_rules',
            ]);
        });
    }
};
