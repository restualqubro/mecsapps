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
        Schema::create('purchases', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 20)->unique();            
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->foreignId('supplier_id')->references('id')->on('suppliers');
            $table->bigInteger('totalharga');
            $table->bigInteger('totalbayar');            
            $table->bigInteger('sisa');
            $table->string('description')->nullable();
            $table->enum('status', ['Lunas', 'Cash', 'Utang']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
