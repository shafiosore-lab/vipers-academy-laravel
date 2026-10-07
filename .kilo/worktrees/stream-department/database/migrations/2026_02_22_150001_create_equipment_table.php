<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('equipment_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('min_quantity')->default(5);
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'damaged'])->default('new');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('sponsor')->nullable();
            $table->boolean('sponsor_compliant')->default(false);
            $table->date('expiry_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'distributed', 'reserved', 'maintenance', 'retired'])->default('available');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
