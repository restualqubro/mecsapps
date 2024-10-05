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
        Schema::create('stockouts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 12);            
            $table->foreignId('category_id')->references('id')->on('stock_categories');
            $table->string('description')->nullable();
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockouts');
    }
};
