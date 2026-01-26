<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Aset (misal: Mikrotik RB750)
            $table->string('serial_number')->nullable()->unique(); // SN Perangkat
            $table->string('type'); // Router, Modem, Switch, Tools, dll
            $table->string('status')->default('available'); // available, in_use, maintenance, broken
            $table->date('purchase_date')->nullable(); // Tanggal Beli
            $table->decimal('price', 15, 0)->nullable(); // Harga Beli
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Foto Aset
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
