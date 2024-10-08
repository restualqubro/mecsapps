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
        Schema::create('selesai_detail_components', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('selesai_id')->references('id')->on('service_selesais');
            $table->foreignUlid('stock_id')->references('id')->on('product_stocks');
            $table->tinyInteger('component_qty');
            $table->bigInteger('hbeli');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selesai_detail_components');
    }
};
