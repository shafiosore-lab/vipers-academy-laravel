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
        Schema::table('attendances', function (Blueprint $table) {
            // Add gender field for reporting (boys/girls)
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('lateness_category');

            // Add school category for reporting (primary, junior_secondary, senior_secondary)
            $table->enum('school_category', ['primary', 'junior_secondary', 'senior_secondary', 'other'])->nullable()->after('gender');

            // Add organization_id for multi-organization support
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null')->after('school_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['gender', 'school_category', 'organization_id']);
        });
    }
};
