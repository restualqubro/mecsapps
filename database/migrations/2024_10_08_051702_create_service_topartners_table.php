<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_topartners', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('service_id')->references('id')->on('service_data');
            $table->foreignId('partner_id')->references('id')->on('partners');            
            $table->enum('status', ['Kirim', 'Proses', 'Cancel', 'Selesai', 'Kembali']);            
            $table->string('update')->nullable();
            $table->unsignedBigInteger('biaya')->nullable();
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas']);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('service_topartners');
    }
};
