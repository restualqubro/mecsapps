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
        Schema::create('service_data', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 10)->unique();            
            $table->foreignUlid('customer_id')->references('id')->on('customers');
            $table->foreignId('category_id')->references('id')->on('service_categories');
            $table->string('merk');
            $table->string('seri');
            $table->string('sn')->nullable();
            $table->string('reference')->nullable();
            $table->string('kelengkapan');
            $table->string('keluhan');
            $table->string('description')->nullable();
            $table->enum('status', ['Baru', 'Proses', 'Selesai', 'Cancel', 'Keluar']);
            $table->enum('penawaran', ['Setuju', 'Tidak'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_data');
    }
};
