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
        Schema::create('pengembalian_parts', function (Blueprint $table) {
            $table->id();
            $table->string('submitted_id', 26);
            $table->string('approval_id', 26)->nullable();
            $table->enum('status', ['Baru', 'Approve', 'Reject']);
            $table->foreignId('peminjaman_id')->references('id')->on('peminjaman_parts');            
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_parts');
    }
};
