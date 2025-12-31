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
        Schema::create('router_proxies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('router_id')->unique(); // Unique identifier for nginx upstream
            $table->string('router_ip'); // PPPoE client IP
            $table->integer('router_port')->default(80);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'is_active']);
            $table->index('router_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_proxies');
    }
};
