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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // PPPoE Authentication
            $table->string('username', 64)->unique();
            $table->string('password');
            $table->string('service_name')->nullable();

            // Profile Integration
            $table->foreignId('ppp_profile_id')->constrained('ppp_profiles')->restrictOnDelete();
            $table->boolean('profile_override')->default(false);

            // Individual Overrides
            $table->string('local_address', 15)->nullable();
            $table->string('remote_address', 15)->nullable();
            $table->string('dns_server')->nullable();
            $table->string('wins_server')->nullable();
            $table->string('incoming_filter')->nullable();
            $table->string('outgoing_filter')->nullable();

            // Rate Limiting Overrides
            $table->string('rate_limit', 50)->nullable();
            $table->integer('rx_rate_limit')->nullable(); // download in Kbps
            $table->integer('tx_rate_limit')->nullable(); // upload in Kbps
            $table->string('burst_limit', 50)->nullable();
            $table->string('burst_threshold', 50)->nullable();
            $table->string('burst_time', 20)->nullable();

            // Customer Information
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('id_number')->nullable();
            $table->enum('customer_type', ['residential', 'business', 'corporate'])->default('residential');

            // Service Management
            $table->string('package_name');
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->date('installation_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'suspended', 'terminated', 'pending'])->default('pending');
            $table->enum('payment_status', ['paid', 'unpaid', 'overdue'])->default('unpaid');

            // Connection Details
            $table->string('caller_id')->nullable();
            $table->boolean('only_one_override')->nullable();
            $table->integer('idle_timeout_override')->nullable();
            $table->integer('keepalive_timeout_override')->nullable();
            $table->integer('session_timeout_override')->nullable();

            // Advanced PPPoE Settings
            $table->enum('bridge_learning_override', ['default', 'yes', 'no'])->nullable();
            $table->integer('bridge_horizon_override')->nullable();
            $table->integer('bridge_path_cost_override')->nullable();
            $table->integer('bridge_port_priority_override')->nullable();

            // Tracking & Monitoring
            $table->datetime('last_login')->nullable();
            $table->datetime('last_logout')->nullable();
            $table->string('last_caller_id')->nullable();
            $table->bigInteger('total_uptime')->default(0); // seconds
            $table->integer('session_count')->default(0);
            $table->bigInteger('bytes_in')->default(0);
            $table->bigInteger('bytes_out')->default(0);

            // Administrative
            $table->string('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            // Indexes for better performance
            $table->index(['username']);
            $table->index(['status']);
            $table->index(['payment_status']);
            $table->index(['customer_type']);
            $table->index(['is_active']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
