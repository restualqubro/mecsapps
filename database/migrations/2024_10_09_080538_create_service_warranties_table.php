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
        Schema::create('service_warranties', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 20);
            $table->foreignUlid('invoice_id')->references('id')->on('invoices');
            $table->string('kelengkapan');
            $table->string('keluhan');
            $table->enum('status', ['Baru', 'Proses', 'Selesai', 'Cancel', 'Keluar']);
            $table->string('update')->nullable();
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_warranties');
    }
};
