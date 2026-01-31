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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->string('position');
            $table->text('bio')->nullable();
            $table->text('achievements')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('has_professional_contract')->default(false);
            $table->string('contract_team')->nullable();
            $table->string('contract_type')->nullable();
            $table->text('milestones')->nullable();
            $table->date('academy_join_date')->nullable();
            $table->string('current_level')->nullable();
            $table->string('preferred_position')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

