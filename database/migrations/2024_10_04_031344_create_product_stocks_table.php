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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('item_id')->references('id')->on('product_items');                    
            $table->string('code', 3);
            $table->bigInteger('hbeli');
            $table->smallInteger('stok');
            $table->smallInteger('supplier_warranty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
