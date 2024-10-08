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
        Schema::create('service_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->foreignUlid('service_id')->references('id')->on('service_data');
            $table->enum('status', ['Baru', 'Proses', 'Selesai', 'Cancel', 'Keluar', 'Kembali']);
            $table->string('description');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_logs');
    }
};
