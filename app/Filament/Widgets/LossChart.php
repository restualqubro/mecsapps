<?php

namespace App\Filament\Widgets;

use App\Models\Finance\Compensation;
use App\Models\Products\StockoutDetails;
use App\Models\Retur\InvoiceRetur;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class LossChart extends ChartWidget
{
    protected static ?string $heading = 'Loss Chart';

    protected function getData(): array
    {        
        $dataRetur = Trend::model(
                        InvoiceRetur::class
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('totalbiaya');  
        $dataKerugian = Trend::query(        
                            StockoutDetails::join('stockouts', 'stockout_details.stockout_id', '=', 'stockouts.id')                                            
                                            ->join('product_stocks', 'stockout_details.stock_id', '=', 'product_stocks.id')                                                                                        
                                            ->where('category_id', '=', 2)
                        ) 
                        ->dateColumn('stockout_details.created_at')
                        ->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('qty * hbeli'); 
        $dataCompensation = Trend::model(
                        Compensation::class
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('nominal');              
        return [
            'datasets' => [                    
                [
                    'label' => 'Invoice Retur',
                    'data' =>   $dataRetur->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(61, 212, 217)',
                ],
                [
                    'label' => 'Kerugian / Item Rusak',
                    'data' =>   $dataKerugian->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(211, 62, 217)',
                ], 
                [
                    'label' => 'Kompensasi',
                    'data' =>   $dataCompensation->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(201, 216, 60)',
                ],  

            ],
            'labels' => $dataRetur->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('M')),
            'height' => '300px',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
