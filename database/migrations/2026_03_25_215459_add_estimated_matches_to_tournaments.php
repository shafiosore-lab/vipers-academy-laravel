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
        Schema::table('tournaments', function (Blueprint $table) {

            if (!Schema::hasColumn('tournaments', 'estimated_matches')) {
                $table->integer('estimated_matches')
                    ->nullable()
                    ->after('competition_format');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {

            if (Schema::hasColumn('tournaments', 'estimated_matches')) {
                $table->dropColumn('estimated_matches');
            }

        });
    }
};
