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
        Schema::create('sale_retur_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('saleretur_id')->references('id')->on('sale_returs');
            $table->foreignId('saledetail_id')->references('id')->on('sale_details');
            $table->tinyInteger('qty');
            $table->bigInteger('hjual');
            $table->bigInteger('disc');
            $table->bigInteger('profit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_retur_details');
    }
};
