<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->decimal('amount', 10, 2);
            $table->string('reference_id', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_credits');
    }
};
