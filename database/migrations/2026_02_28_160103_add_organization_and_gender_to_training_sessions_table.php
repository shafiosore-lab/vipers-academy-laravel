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
        Schema::table('training_sessions', function (Blueprint $table) {
            // Add gender field for school management (boys/girls teams)
            $table->enum('gender', ['male', 'female', 'mixed'])->nullable()->after('team_category');

            // Add organization_id for multi-school/organization support
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null')->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['gender', 'organization_id']);
        });
    }
};
