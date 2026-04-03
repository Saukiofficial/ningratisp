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
        Schema::create('customer_installation_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->foreignId('ppp_area_id')->nullable()->constrained('ppp_areas')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->cascadeOnDelete();
            $table->enum('status', ['pending', 'onprogress', 'done', 'cancelled']);
            $table->string('maps_link')->nullable();
            $table->timestamp('installation_date')->nullable();
            $table->timestamp('finished_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->string('reffer_by')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_installation_orders');
    }
};
