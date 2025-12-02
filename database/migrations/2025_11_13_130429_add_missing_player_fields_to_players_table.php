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
            $table->boolean('has_professional_contract')->default(false);
            $table->string('contract_team')->nullable();
            $table->string('contract_type')->nullable();
            $table->text('milestones')->nullable();
            $table->date('academy_join_date')->nullable();
            $table->string('current_level')->nullable();
            $table->string('preferred_position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'has_professional_contract',
                'contract_team',
                'contract_type',
                'milestones',
                'academy_join_date',
                'current_level',
                'preferred_position'
            ]);
        });
    }
};
