<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name');
            $table->string('gateway_type'); // sms, whatsapp, talksasa, africaastalking
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->boolean('is_primary')->default(false);
            $table->text('description')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('account_id')->nullable();
            $table->text('settings')->nullable(); // JSON settings
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Insert default gateways
        DB::table('message_gateways')->insert([
            [
                'gateway_name' => 'Advanta SMS',
                'gateway_type' => 'sms',
                'status' => 'active',
                'is_primary' => false,
                'description' => 'Local Kenyan SMS provider, good for local messaging, competitive local rates',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_name' => "Africa's Talking",
                'gateway_type' => 'sms',
                'status' => 'active',
                'is_primary' => true,
                'description' => "Pan-African SMS provider, international coverage, advanced APIs",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_name' => 'WhatsApp',
                'gateway_type' => 'whatsapp',
                'status' => 'inactive',
                'is_primary' => false,
                'description' => 'Use WhatsApp Business API, for bulk & direct messages, international coverage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_name' => 'TalkSasa',
                'gateway_type' => 'talksasa',
                'status' => 'inactive',
                'is_primary' => false,
                'description' => 'Local Kenyan messaging platform, competitive rates, ideal for youth-focused campaigns',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('message_gateways');
    }
};
