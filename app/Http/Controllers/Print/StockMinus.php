<?php

namespace App\Http\Controllers\print;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductStocks;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Storage;

class StockMinus extends Controller
{
    public function print(GeneralSettings $settings) 
    {
        $categoryid = request('tableFilters.category_id.value');
        if (!$categoryid) 
        {
            $items = ProductStocks::where('stok', '<=', 1)->orderBy('id', 'desc')->get();        
        }  else {
            $items = ProductStocks::whereHas('product', fn($q) => $q->where('category_id', $categoryid))->where('stok', '<=', 1)->orderBy('id', 'desc')->get();
        }
        $data = [
            'title'     => 'STOK MINUS',
            'logo'      => Storage::url($settings->brand_logo),
            'item'      => $items,
            'count'     => $items->count(),
            'dateTime'  => \Carbon\Carbon::now(),
            'users'     => auth()->user()->name,
        ];
    	return view('print.stockminus', $data);
    }
}
