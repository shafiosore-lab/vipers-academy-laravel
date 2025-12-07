<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for search performance
        // Schema::table('players', function (Blueprint $table) {
        //     $table->index('position');
        //     $table->index('category');
        //     $table->index(['position', 'age']); // For ordering
        // });

        Schema::table('programs', function (Blueprint $table) {
            $table->index('title');
            $table->index('description');
            $table->index('age_group');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->index('title');
            $table->index('content');
            $table->index('category');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
