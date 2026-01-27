<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->enum('customer_category', ['free_forever', 'loan', 'normal'])->default('normal')->index();
            $table->date('loan_end_date')->nullable()->index();
            $table->integer('loan_months')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn(['discount_id', 'customer_category', 'loan_end_date', 'loan_months']);
        });
    }
};
