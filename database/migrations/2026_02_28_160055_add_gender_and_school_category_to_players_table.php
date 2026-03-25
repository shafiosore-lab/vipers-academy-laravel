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
            // Add school category for school players (primary, junior secondary, senior secondary)
            // Note: gender and organization_id already exist in the table
            if (!Schema::hasColumn('players', 'school_category')) {
                $table->enum('school_category', ['primary', 'junior_secondary', 'senior_secondary', 'other'])->nullable()->after('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['school_category']);
        });
    }
};
