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
        Schema::table('programs', function (Blueprint $table) {
            $table->string('fee_display')->nullable()->after('mumias_fee');
            $table->string('schedule_display')->nullable()->after('fee_display');
            $table->string('age_range')->nullable()->after('schedule_display');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['fee_display', 'schedule_display', 'age_range']);
        });
    }
};
