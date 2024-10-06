<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER tr_purchase_edited AFTER UPDATE ON `purchase_details` FOR EACH ROW
            BEGIN
                UPDATE product_stocks SET product_stocks.stok = product_stocks.stok - OLD.qty + NEW.qty
                WHERE id = NEW.stock_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DROP TRIGGER `tr_purchase_edited`');
    }
};
