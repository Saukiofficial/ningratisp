<?php

use App\Models\Voucher;
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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->unique();
            $table->string('code', 10)->unique();
            $table->timestampTz('expired_at');
            $table->text('description')->nullable();
            $table->integer('duration')->default(0);
            $table->string('duration_type')->default(Voucher::HOUR);
            $table->boolean('status')->default(true);
            $table->text('external_link')->nullable();
            $table->float('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
