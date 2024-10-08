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
        Schema::create('service_cancels', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('service_id')->references('id')->on('service_data');
            $table->foreignUlid('teknisi_id')->references('id')->on('users');
            $table->string('alasan');
            $table->boolean('isKeluar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_cancels');
    }
};
