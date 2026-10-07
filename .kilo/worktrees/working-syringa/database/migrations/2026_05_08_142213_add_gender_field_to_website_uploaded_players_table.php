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
            // Add gender field with enum-like constraint
            $table->enum('gender', ['M', 'F'])->nullable()->after('category');

            // Add indexes for high-performance querying
            $table->index('gender');
            $table->index('category');
            $table->index(['gender', 'category']); // Composite index for combined filtering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            $table->dropIndex(['gender', 'category']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['category']);
            $table->dropColumn('gender');
        });
    }
};
