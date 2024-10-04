<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER tr_stockins_added AFTER INSERT ON `stockin_details` FOR EACH ROW
            BEGIN
                UPDATE product_stocks SET product_stocks.stok = product_stocks.stok + NEW.qty
                WHERE id = NEW.stock_id;
            END
        ');    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DROP TRIGGER `tr_stockins_added`');
    }
};
