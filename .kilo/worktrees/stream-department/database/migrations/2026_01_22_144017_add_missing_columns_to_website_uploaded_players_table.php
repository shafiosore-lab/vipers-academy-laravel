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
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            if (!Schema::hasColumn('website_uploaded_players', 'jersey_number')) {
                $table->string('jersey_number')->nullable()->after('position');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'goals')) {
                $table->integer('goals')->default(0)->after('jersey_number');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'assists')) {
                $table->integer('assists')->default(0)->after('goals');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'appearances')) {
                $table->integer('appearances')->default(0)->after('assists');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            $table->dropColumn(['jersey_number', 'goals', 'assists', 'appearances']);
        });
    }
};
