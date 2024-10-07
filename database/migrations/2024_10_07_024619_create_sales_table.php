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
        Schema::create('sales', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 20);            
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->foreignUlid('customer_id')->references('id')->on('customers');            
            $table->bigInteger('total');
            $table->bigInteger('totaldiscount');
            $table->bigInteger('totalbayar');
            $table->bigInteger('sisa');
            $table->enum('status', ['Lunas', 'Cash', 'Piutang']);
            $table->boolean('is_pending');            
            $table->string('description')->nullable();
            $table->bigInteger('preorder_id')->nullable();
            $table->bigInteger('totalpreorder')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
