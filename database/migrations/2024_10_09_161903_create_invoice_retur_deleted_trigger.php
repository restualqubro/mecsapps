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
        CREATE TRIGGER tr_invoice_returs_deleted AFTER DELETE ON `invoice_retur_details` FOR EACH ROW
                BEGIN                    
                    UPDATE selesai_detail_catalogs SET                     
                    selesai_detail_catalogs.catalog_qty = selesai_detail_catalogs.catalog_qty + OLD.qty
                    WHERE id = OLD.selesaidetailcatalog_id;
                END
        ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_invoice_returs_deleted`');
    }
};
