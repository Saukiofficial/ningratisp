<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'overpaid', 'refunded'])->default('unpaid');
            $table->enum('invoice_type', ['monthly', 'loan_settlement', 'adjustment'])->default('monthly');
            $table->index(['payment_status', 'invoice_type']);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'invoice_type']);
            $table->dropForeign(['discount_id']);
            $table->dropColumn(['discount_id', 'discount_amount', 'subtotal', 'paid_amount', 'balance_due', 'payment_status', 'invoice_type']);
        });
    }
};
