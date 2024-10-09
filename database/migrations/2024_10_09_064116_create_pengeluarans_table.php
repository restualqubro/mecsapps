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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('finance_categories');
            $table->bigInteger('nominal');
            $table->string('description')->nullable();
            $table->enum('status', ['Baru', 'Approve', 'Reject']);
            $table->string('submitted_id');
            $table->string('approval_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
