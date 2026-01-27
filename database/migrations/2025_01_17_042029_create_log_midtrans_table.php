<?php

use App\Models\LogMidtrans;
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
        Schema::create('log_midtrans', function (Blueprint $table) {
            $table->id();
            $table->string('orderid');
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->string('act')->default(LogMidtrans::REQUEST);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_midtrans');
    }
};
