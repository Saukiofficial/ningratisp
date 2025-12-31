<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed_amount']);
            $table->decimal('value', 10, 2);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->enum('applicable_to', ['invoice', 'package', 'customer']);
            $table->enum('customer_category', ['free_forever', 'loan', 'normal'])->nullable()->index();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_apply')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'auto_apply']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
