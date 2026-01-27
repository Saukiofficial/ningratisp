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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('total_amount');
            $table->string('reference_id', 100);
            $table->timestampTz('payment_datetime');
            $table->boolean('is_cancel')->default(false);
            $table->text('description')->nullable();
            $table->foreignId('voucher_id')->references('id')->on('vouchers')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
