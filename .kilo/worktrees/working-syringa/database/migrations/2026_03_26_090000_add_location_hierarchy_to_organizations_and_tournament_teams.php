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
        // Add location hierarchy fields to organizations table
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('country', 10)->nullable()->after('address');
            $table->string('county', 100)->nullable()->after('country');
            $table->string('sub_county', 100)->nullable()->after('county');
            $table->string('ward', 100)->nullable()->after('sub_county');
            $table->enum('location_level', ['country', 'county', 'sub_county', 'ward'])->nullable()->after('ward');
        });

        // Add location hierarchy fields to tournament_teams table
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->string('country', 10)->nullable()->after('team_contact_phone');
            $table->string('county', 100)->nullable()->after('country');
            $table->string('sub_county', 100)->nullable()->after('county');
            $table->string('ward', 100)->nullable()->after('sub_county');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->dropColumn(['country', 'county', 'sub_county', 'ward']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['country', 'county', 'sub_county', 'ward', 'location_level']);
        });
    }
};
