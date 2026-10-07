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
        Schema::create('organization_letterheads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('name')->default('Default Letterhead');
            $table->string('style')->default('classic'); // classic, modern, minimal
            $table->string('header_alignment')->default('left'); // left, center
            $table->string('primary_color')->default('#ea1c4d'); // Default primary color
            $table->boolean('show_watermark')->default(true);
            $table->boolean('show_page_numbers')->default(true);
            $table->text('custom_footer_note')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('signature_name')->nullable();
            $table->string('signature_title')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->index('organization_id');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_documents');
        Schema::dropIfExists('organization_letterheads');
    }
};
