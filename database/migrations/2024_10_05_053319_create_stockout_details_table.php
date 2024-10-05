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
        Schema::create('stockout_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('stockout_id')->references('id')->on('stockouts');
            $table->foreignUlid('stock_id')->references('id')->on('product_stocks');            
            $table->tinyInteger('qty');   
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockout_details');
    }
};
