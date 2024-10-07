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
        CREATE TRIGGER tr_sales_added AFTER INSERT ON `sale_details` FOR EACH ROW
                BEGIN                    
                    UPDATE product_stocks SET                     
                    product_stocks.stok = product_stocks.stok - NEW.qty
                    WHERE id = NEW.stock_id;
                END
        ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_sales_added`');
    }
};
