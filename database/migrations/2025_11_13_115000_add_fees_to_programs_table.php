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
            $table->string('duration')->nullable()->after('schedule');
            $table->decimal('regular_fee', 10, 2)->nullable()->after('duration');
            $table->decimal('mumias_fee', 10, 2)->nullable()->after('regular_fee');
            $table->integer('mumias_discount_percentage')->default(50)->after('mumias_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['duration', 'regular_fee', 'mumias_fee', 'mumias_discount_percentage']);
        });
    }
};
