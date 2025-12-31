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
        Schema::create('customer_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('unknown');
            $table->unsignedInteger('ping_count')->default(0);
            $table->unsignedInteger('packet_loss')->default(0);
            $table->string('avg_rtt')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->text('last_ping_output')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_connections');
    }
};
