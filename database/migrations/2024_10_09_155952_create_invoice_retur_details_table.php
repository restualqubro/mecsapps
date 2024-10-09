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
        Schema::create('invoice_retur_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('returinvoice_id')->references('id')->on('invoice_returs');
            $table->foreignId('selesaidetailcatalog_id')->references('id')->on('selesai_detail_catalogs');
            $table->tinyInteger('qty');
            $table->bigInteger('biaya');
            $table->bigInteger('disc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_retur_details');
    }
};
