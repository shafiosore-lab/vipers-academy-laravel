<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->decimal('total_paid', 12, 2)->default(0)->after('payment_category_id');
            $table->decimal('program_total_paid', 12, 2)->default(0)->after('total_paid');
            $table->decimal('monthly_total_paid', 12, 2)->default(0)->after('program_total_paid');
            $table->decimal('amount_owing', 12, 2)->default(0)->after('monthly_total_paid');
            $table->decimal('program_amount_owing', 12, 2)->default(0)->after('amount_owing');
            $table->decimal('monthly_amount_owing', 12, 2)->default(0)->after('program_amount_owing');
            $table->date('last_payment_date')->nullable()->after('monthly_amount_owing');
            $table->integer('months_owing')->default(0)->after('last_payment_date');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'total_paid',
                'program_total_paid',
                'monthly_total_paid',
                'amount_owing',
                'program_amount_owing',
                'monthly_amount_owing',
                'last_payment_date',
                'months_owing',
            ]);
        });
    }
};
