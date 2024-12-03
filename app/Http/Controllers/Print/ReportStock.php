<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductBrands;
use App\Models\Products\ProductCategories;
use App\Models\Products\ProductStocks;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportStock extends Controller
{
    public function print(GeneralSettings $settings) 
    {                          
        $category = request('tableFilters.category_id.values');
        $brand = request('tableFilters.brand_id.values');
        $valueRange = request('tableFilters.stok');
        $get = ProductStocks::has('item')      
                                ->get()                                                                                          
                                ;
        $items =  $get
            ->when($category, 
                fn($query) => $query->whereIn('item.category_id', $category)
            )
            ->when($brand, 
                fn($query) => $query->whereIn('item.brand_id', $brand)
            )
            ->when($valueRange, function ($query, $valueRange) {
                return $query
                    ->when(
                        $valueRange['range_condition'] == 'equal',
                        fn ($query) => $query->where('stok', '=', $valueRange['range_equal']),
                    )                  
                    ->when(
                        $valueRange['range_condition'] === 'not_equal',
                        fn ($query) => $query->where('stok', '!=', $valueRange['range_not_equal']),
                    )
                    ->when(
                        $valueRange['range_condition'] === 'between',
                        function ($query) use ($valueRange) {
                            $query->where('stok', '>=', $valueRange['range_between_from'])->where('stok', '<=', $valueRange['range_between_to']);
                        },
                    )
                    ->when(
                        $valueRange['range_condition'] === 'greater_than',
                        fn ($query) => $query->where('stok', '>', $valueRange['range_greater_than']),
                    )
                    ->when(
                        $valueRange['range_condition'] === 'greater_than_equal',
                        fn ($query) => $query->where('stok', '>=', $valueRange['range_greater_than_equal']),
                    )
                    ->when(
                        $valueRange['range_condition'] == 'less_than',
                        fn ($query) => $query->where('stok', '<', $valueRange['range_less_than']),
                    )
                    ->when(
                        $valueRange['range_condition'] == 'less_than_equal',
                        fn ($query) => $query->where('stok', '<=', $valueRange['range_less_than_equal']),
                    );                    
            });                                
        if ($category) {
            $productCategories = ProductCategories::find($category)->pluck('name')->implode(", ");
        } else {
            $productCategories = '-';
        }
        if ($brand) {
            $productBrands = ProductBrands::find($brand)->pluck('name')->implode(", ");
        } else {
            $productBrands = '=';
        }
        if ($valueRange) {
            if ($valueRange['range_condition'] == 'between') 
            {                
                $stok = $valueRange['range_condition']." ".$valueRange['range_between_from']." to ".$valueRange['range_between_to'];
            } else {
                $stok = implode(" ", $valueRange);
            }            
        } else {
            $stok = 'dsa';
        }
        
        $data = [
            "title"             => "Product Stocks",
            "items"             => $items,
            "count"             => $items->count(),
            "dateTime"          => Carbon::now(),
            "logo"              => Storage::url($settings->brand_logo),
            "request"           => request('tableFilters'),
            "productCategories" => $productCategories,
            "productBrands"     => $productBrands,
            "stok"              => $stok        
        ];

        return View('print.productstocks', $data);        
    }
}
