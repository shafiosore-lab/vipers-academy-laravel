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
        Schema::create('organization_brandings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('primary_color')->default('#007bff');
            $table->string('secondary_color')->default('#6c757d');
            $table->string('accent_color')->default('#28a745');
            $table->string('font_family')->default('Arial, sans-serif');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('header_logo_path')->nullable();
            $table->string('footer_logo_path')->nullable();
            $table->json('letterhead_template')->nullable();
            $table->json('email_template')->nullable();
            $table->json('document_template')->nullable();
            $table->json('social_media_links')->nullable();
            $table->json('brand_guidelines')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('organization_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_brandings');
    }
};
