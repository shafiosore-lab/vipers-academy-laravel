<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('export_type'); // payment, order, subscription, etc.
            $table->string('report_name');
            $table->string('file_format'); // pdf, excel, csv
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('record_count')->default(0);
            $table->json('email_recipients')->nullable();
            $table->string('ip_address')->nullable();
            $table->enum('status', ['success', 'failed', 'email_sent', 'email_failed']);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logs');
    }
};
