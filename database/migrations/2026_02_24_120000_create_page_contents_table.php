<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page', 100)->index(); // e.g., 'about', 'home', 'contact'
            $table->string('section', 100)->index(); // e.g., 'journey', 'values', 'hero'
            $table->string('key', 100)->index(); // e.g., 'title', 'description', 'year_2017'
            $table->text('value')->nullable();
            $table->string('type', 50)->default('text'); // text, textarea, number, json
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint for page + section + key combination
            $table->unique(['page', 'section', 'key'], 'page_content_unique');
        });

        // Seed default about page content
        $this->seedDefaultContent();
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }

    private function seedDefaultContent(): void
    {
        // Journey timeline entries
        $journeyEntries = [
            ['key' => 'year_2017', 'value' => 'Founded as a grassroots initiative to nurture football talent and discipline.', 'sort_order' => 1],
            ['key' => 'year_2019', 'value' => 'Expanded training programs and community engagement.', 'sort_order' => 2],
            ['key' => 'year_2021', 'value' => 'First players progressed to competitive clubs and secondary schools.', 'sort_order' => 3],
            ['key' => 'year_2024', 'value' => '20+ players on sports scholarships; expanding partnerships and facilities.', 'sort_order' => 4],
        ];

        foreach ($journeyEntries as $entry) {
            \DB::table('page_contents')->insert([
                'page' => 'about',
                'section' => 'journey',
                'key' => $entry['key'],
                'value' => $entry['value'],
                'type' => 'textarea',
                'sort_order' => $entry['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Core values entries
        $valuesEntries = [
            ['key' => 'discipline', 'value' => 'Consistency, responsibility, and self-control on and off the pitch.', 'icon' => '🎯', 'sort_order' => 1],
            ['key' => 'respect', 'value' => 'For teammates, parents, coaches, and the wider community.', 'icon' => '🤝', 'sort_order' => 2],
            ['key' => 'hard_work', 'value' => 'Growth through commitment, effort, and perseverance.', 'icon' => '💪', 'sort_order' => 3],
            ['key' => 'child_safety', 'value' => 'Safe environments, parental involvement, and transparent operations.', 'icon' => '🛡️', 'sort_order' => 4],
        ];

        foreach ($valuesEntries as $entry) {
            \DB::table('page_contents')->insert([
                'page' => 'about',
                'section' => 'values',
                'key' => $entry['key'],
                'value' => $entry['value'],
                'type' => 'json',
                'sort_order' => $entry['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Section titles
        \DB::table('page_contents')->insert([
            ['page' => 'about', 'section' => 'journey', 'key' => 'title', 'value' => 'Our Journey', 'type' => 'text', 'sort_order' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['page' => 'about', 'section' => 'values', 'key' => 'title', 'value' => 'Our Core Values', 'type' => 'text', 'sort_order' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};
