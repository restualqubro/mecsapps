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
        Schema::create('stockin_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('stockin_id')->references('id')->on('stockins')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUlid('stock_id')->references('id')->on('product_stocks');            
            $table->string('name');
            $table->smallInteger('qty');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_stockins');
    }
};
