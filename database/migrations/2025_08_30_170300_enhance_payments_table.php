<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->enum('payment_type', ['incoming', 'refund'])->default('incoming');
            $table->index(['payment_type']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['payment_type']);
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['invoice_id', 'payment_method_id', 'payment_type']);
        });
    }
};
