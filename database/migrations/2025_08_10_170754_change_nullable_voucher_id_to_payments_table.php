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
        Schema::table('payments', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['voucher_id']);

            // Modify the column to be nullable
            $table->foreignId('voucher_id')->nullable()->change();

            // Recreate the foreign key constraint
            $table->foreign('voucher_id')->references('id')->on('vouchers')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['voucher_id']);

            // Modify the column to be non-nullable
            $table->foreignId('voucher_id')->nullable(false)->change();

            // Recreate the foreign key constraint
            $table->foreign('voucher_id')->references('id')->on('vouchers')->restrictOnDelete();
        });
    }
};
