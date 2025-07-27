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
        Schema::create('ppp_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('profile_name')->unique();
            $table->string('local_address', 15)->nullable();
            $table->string('remote_address', 15)->nullable();
            $table->string('dns_server')->nullable();
            $table->string('wins_server')->nullable();
            $table->string('rate_limit', 50)->nullable(); // format: "upload/download"
            $table->string('burst_limit', 50)->nullable();
            $table->string('burst_threshold', 50)->nullable();
            $table->string('burst_time', 20)->nullable();
            $table->integer('session_timeout')->nullable();
            $table->integer('idle_timeout')->nullable();
            $table->integer('keepalive_timeout')->nullable();
            $table->boolean('only_one')->default(false);
            $table->string('incoming_filter')->nullable();
            $table->string('outgoing_filter')->nullable();
            $table->enum('bridge_learning', ['default', 'yes', 'no'])->default('default');
            $table->integer('bridge_horizon')->nullable();
            $table->integer('bridge_path_cost')->nullable();
            $table->integer('bridge_port_priority')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppp_profiles');
    }
};
