<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Transactions\Sale;
// use App\Models\Finance\BankAccount;
use App\Models\Transactions\SaleDetails;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FakturJual extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $data = [
            'title'     => 'Faktur Penjualan',
            'jual'      => Sale::find($id),
            'items'     => SaleDetails::where('jual_id', $id)->get(),
            'logo'      => Storage::url($settings->brand_logo),
            'site'      => $settings->brand_name,
            // 'banks'     => BankAccount::all(),
        ];  
        // return "adasdas"  	;
    	return View('print.fakturjual', $data);
    }
}
