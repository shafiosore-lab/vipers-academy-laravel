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
        Schema::table('page_contents', function (Blueprint $table) {
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->onDelete('cascade')
                ->after('id');

            // Add index for better query performance
            $table->index(['organization_id', 'page', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id', 'page', 'section']);
            $table->dropColumn('organization_id');
        });
    }
};
