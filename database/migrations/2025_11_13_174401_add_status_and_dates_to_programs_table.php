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
            $table->enum('status', ['active', 'inactive', 'upcoming', 'completed'])->default('active')->after('schedule');
            $table->date('start_date')->nullable()->after('status');
            $table->date('end_date')->nullable()->after('start_date');
            $table->integer('max_participants')->nullable()->after('end_date');
            $table->decimal('program_fee', 10, 2)->nullable()->after('max_participants');
            $table->text('objectives')->nullable()->after('program_fee');
            $table->string('difficulty_level')->nullable()->after('objectives');
            $table->json('schedule_details')->nullable()->after('difficulty_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'start_date',
                'end_date',
                'max_participants',
                'program_fee',
                'objectives',
                'difficulty_level',
                'schedule_details'
            ]);
        });
    }
};
