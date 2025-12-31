<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('payment_type', ['incoming', 'refund', 'voucher'])->default('incoming')->change();
            });
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_type_check");
            DB::statement("ALTER TABLE payments ADD CONSTRAINT payments_payment_type_check CHECK (payment_type IN ('incoming', 'refund', 'voucher'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('payment_type', ['incoming', 'refund'])->default('incoming')->change();
            });
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_type_check");
            DB::statement("ALTER TABLE payments ADD CONSTRAINT payments_payment_type_check CHECK (payment_type IN ('incoming', 'refund'))");
        }
    }
};
