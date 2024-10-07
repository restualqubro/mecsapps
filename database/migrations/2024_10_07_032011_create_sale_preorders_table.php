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
        Schema::create('sale_preorders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->foreignUlid('customer_id')->references('id')->on('customers');
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->bigInteger('nominal');
            $table->string('description');            
            $table->string('estimasi', 50);
            $table->enum('status', ['Baru', 'Selesai', 'Cancel']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_preorders');
    }
};
