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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('purchase_id')->references('id')->on('purchases');
            $table->foreignUlid('stock_id')->references('id')->on('product_stocks');
            $table->smallInteger('qty');
            $table->bigInteger('hbeli');
            $table->smallInteger('supplier_warranty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
