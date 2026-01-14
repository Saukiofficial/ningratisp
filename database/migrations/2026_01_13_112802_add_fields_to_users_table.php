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
        // --- BAGIAN 1: Update Tabel Users ---
        Schema::table('users', function (Blueprint $table) {
            // 1. Hapus kolom role jika sudah ada dari migrasi sebelumnya yang mungkin tipe datanya salah
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // 2. Buat ulang kolom role dengan tipe string yang benar
            $table->string('role')->default('customer')->after('email');

            // 3. Tambahkan kolom status, address, dan package_id
            $table->string('status')->default('active')->after('role'); // active, suspended, inactive
            $table->text('address')->nullable()->after('status');
            $table->foreignId('package_id')->nullable()->after('address')->constrained('packages')->nullOnDelete();
        });

        // --- BAGIAN 2: Update Tabel Complaints (Fix Error Missing Columns) ---
        Schema::table('complaints', function (Blueprint $table) {
            // Tambahkan user_id jika belum ada
            if (!Schema::hasColumn('complaints', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            }

            // Tambahkan subject jika belum ada
            if (!Schema::hasColumn('complaints', 'subject')) {
                $table->string('subject')->after('user_id');
            }

            // Tambahkan description jika belum ada
            if (!Schema::hasColumn('complaints', 'description')) {
                $table->text('description')->after('subject');
            }

            // Tambahkan status jika belum ada
            if (!Schema::hasColumn('complaints', 'status')) {
                $table->string('status')->default('pending')->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign(['user_id']);

            // Drop semua kolom tambahan
            $table->dropColumn(['user_id', 'subject', 'description', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['status', 'address', 'package_id', 'role']);
        });
    }
};
