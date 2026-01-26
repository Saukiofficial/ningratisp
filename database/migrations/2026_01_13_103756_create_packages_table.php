<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('speed'); // Contoh: "100 Mbps"
            $table->unsignedBigInteger('price'); // Ubah dari decimal ke bigInteger untuk hilangkan desimal
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // Disimpan sebagai JSON
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
