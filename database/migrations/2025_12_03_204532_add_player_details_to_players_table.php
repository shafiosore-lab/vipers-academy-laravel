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
            // Check and add columns if they don't exist
            if (!Schema::hasColumn('players', 'category')) {
                $table->enum('category', ['under-13', 'under-15', 'under-17', 'senior'])->after('last_name');
            }

            if (!Schema::hasColumn('players', 'position')) {
                $table->enum('position', ['goalkeeper', 'defender', 'midfielder', 'striker'])->after('category');
            }

            if (!Schema::hasColumn('players', 'age')) {
                $table->integer('age')->after('position');
            }

            if (!Schema::hasColumn('players', 'jersey_number')) {
                $table->string('jersey_number')->nullable()->after('age');
            }

            if (!Schema::hasColumn('players', 'image_path')) {
                $table->string('image_path')->nullable()->after('jersey_number');
            }

            if (!Schema::hasColumn('players', 'bio')) {
                $table->text('bio')->nullable()->after('image_path');
            }

            if (!Schema::hasColumn('players', 'goals')) {
                $table->integer('goals')->default(0)->after('bio');
            }

            if (!Schema::hasColumn('players', 'assists')) {
                $table->integer('assists')->default(0)->after('goals');
            }

            if (!Schema::hasColumn('players', 'appearances')) {
                $table->integer('appearances')->default(0)->after('assists');
            }

            if (!Schema::hasColumn('players', 'yellow_cards')) {
                $table->integer('yellow_cards')->default(0)->after('appearances');
            }

            if (!Schema::hasColumn('players', 'red_cards')) {
                $table->integer('red_cards')->default(0)->after('yellow_cards');
            }
        });

        // Add indexes for better performance
        Schema::table('players', function (Blueprint $table) {
            if (!Schema::hasIndex('players', ['players_category_index'])) {
                $table->index('category');
            }
            if (!Schema::hasIndex('players', ['players_position_index'])) {
                $table->index('position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'position',
                'age',
                'jersey_number',
                'image_path',
                'bio',
                'goals',
                'assists',
                'appearances',
                'yellow_cards',
                'red_cards'
            ]);
        });
    }
};
