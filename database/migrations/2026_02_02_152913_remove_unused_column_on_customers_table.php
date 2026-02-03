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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'dns_server',
                'wins_server',
                'incoming_filter',
                'outgoing_filter',
                'rate_limit',
                'rx_rate_limit',
                'tx_rate_limit',
                'burst_threshold',
                'burst_limit',
                'burst_time',
                'payment_status',
                'caller_id',
                'idle_timeout_override',
                'keepalive_timeout_override',
                'session_timeout_override',
                'bridge_learning_override',
                'bridge_horizon_override',
                'bridge_path_cost_override',
                'bridge_port_priority_override',
                'last_login',
                'last_logout',
                'last_caller_id',
                'total_uptime',
                'session_count',
                'bytes_in',
                'bytes_out',

                'profile_override',
                'customer_type',
                'package_name',
                'monthly_fee',
                'only_one_override',
                'created_by',
                'loan_end_date',
                'loan_months'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
