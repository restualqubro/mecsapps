<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
// use App\Models\Finance\BankAccount;
use App\Models\Transactions\SalePreorders;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FakturPreorder extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $data = [
            'title'     => 'FAKTUR PREORDER',
            'items'     => SalePreorders::find($id),
            'logo'      => Storage::url($settings->brand_logo),
            'site'      => $settings->brand_name,
            // 'banks'     => BankAccount::all(),
        ];                
    	return View('print.fakturpreorder', $data);
    }
}
