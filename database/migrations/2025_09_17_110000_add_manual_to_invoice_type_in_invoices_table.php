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
        // Using raw SQL statements to handle database-specific syntax for modifying enums/constraints,
        // as Laravel's change() method can be unreliable with enums on PostgreSQL.

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL requires redefining the entire ENUM list.
            Schema::table('invoices', function (Blueprint $table) {
                // Add 'manual' to the existing enum values.
                // This requires the doctrine/dbal package.
                $table->enum('invoice_type', ['monthly', 'loan_settlement', 'adjustment', 'manual'])->change();
            });
        }

        if ($driver === 'pgsql') {
            // PostgreSQL uses a CHECK constraint for enums created via Laravel's schema builder.
            // We need to drop the old constraint and create a new one with the added value.
            DB::statement("ALTER TABLE invoices DROP CONSTRAINT IF EXISTS invoices_invoice_type_check");
            DB::statement("ALTER TABLE invoices ADD CONSTRAINT invoices_invoice_type_check CHECK (invoice_type IN ('monthly', 'loan_settlement', 'adjustment', 'manual'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing this can fail if the 'manual' enum value is in use in the table.
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::table('invoices', function (Blueprint $table) {
                // Add 'manual' to the existing enum values.
                // This requires the doctrine/dbal package.
                $table->enum('invoice_type', ['monthly', 'loan_settlement', 'adjustment'])->change();
            });
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE invoices DROP CONSTRAINT IF EXISTS invoices_invoice_type_check");
            DB::statement("ALTER TABLE invoices ADD CONSTRAINT invoices_invoice_type_check CHECK (invoice_type IN ('monthly', 'loan_settlement', 'adjustment'))");
        }
    }
};
