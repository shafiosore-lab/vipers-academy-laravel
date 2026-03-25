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
        Schema::table('players', function (Blueprint $table) {
            // WhatsApp notification preferences
            $table->boolean('parent_whatsapp')->nullable()->default(false)->after('parent_phone');
            $table->boolean('whatsapp_opt_in')->nullable()->default(false)->after('parent_whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['parent_whatsapp', 'whatsapp_opt_in']);
        });
    }
};
