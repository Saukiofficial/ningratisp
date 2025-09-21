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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->restrictOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->restrictOnDelete();
            $table->string('va_number')->nullable();
            $table->string('qris_url')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('expired_at');
            $table->string('status')->default('pending');
            $table->string('qris_action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};
