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
        Schema::table('website_players', function (Blueprint $table) {
            $table->integer('yellow_cards')->default(0)->after('appearances');
            $table->integer('red_cards')->default(0)->after('yellow_cards');
            $table->string('youtube_url')->nullable()->after('red_cards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_players', function (Blueprint $table) {
            $table->dropColumn(['yellow_cards', 'red_cards', 'youtube_url']);
        });
    }
};
