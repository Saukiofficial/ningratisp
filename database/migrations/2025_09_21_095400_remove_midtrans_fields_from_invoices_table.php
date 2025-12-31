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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_va_number',
                'midtrans_expiry_time',
                'midtrans_status',
                'midtrans_qris_url',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->string('midtrans_va_number')->nullable();
            $table->timestamp('midtrans_expiry_time')->nullable();
            $table->string('midtrans_status')->nullable();
            $table->text('midtrans_qris_url')->nullable();
        });
    }
};