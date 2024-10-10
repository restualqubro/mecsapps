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
        CREATE TRIGGER tr_sale_returs_edited AFTER UPDATE ON `sale_retur_details` FOR EACH ROW
                BEGIN                    
                    UPDATE sale_details SET                     
                    sale_details.qty = sale_details.qty + OLD.qty - NEW.qty,
                    sale_details.profit = sale_details.profit + ((sale_details.profit / sale_details.qty) * OLD.qty) - ((sale_details.profit / sale_details.qty) * NEW.qty)
                    WHERE id = NEW.selesaidetail_id;
                END
        ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_sale_returs_edited`');
    }
};
