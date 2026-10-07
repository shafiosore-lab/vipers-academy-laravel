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
            $table->string('category')->after('last_name');
            $table->integer('age')->after('category');
            $table->string('jersey_number')->nullable()->after('age');
            $table->integer('goals')->default(0)->after('jersey_number');
            $table->integer('assists')->default(0)->after('goals');
            $table->integer('appearances')->default(0)->after('assists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            $table->dropColumn(['category', 'age', 'jersey_number', 'goals', 'assists', 'appearances']);
        });
    }
};
