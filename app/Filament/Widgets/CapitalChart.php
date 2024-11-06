<?php

namespace App\Filament\Widgets;

use App\Models\Services\ServiceTopartner;
use App\Models\Transactions\Purchase;
use App\Models\Transactions\PurchaseUtang;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CapitalChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {        
        $dataPurchase = Trend::query(
                        Purchase::where('status', '=', 'Cash')
                        ) 
                        ->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('totalharga');  
        $dataTopartner = Trend::query(
                        ServiceTopartner::where('status_pembayaran', '=', 'Lunas')
                        ) 
                        ->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('biaya'); 
        $dataPelunasan = Trend::model(
                        PurchaseUtang::class
                        ) 
                        ->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('bayar');              
        return [
            'datasets' => [                    
                [
                    'label' => 'Pembelian/Purchase',
                    'data' =>   $dataPurchase->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(61, 212, 217)',
                ],
                [
                    'label' => 'Service To partner',
                    'data' =>   $dataTopartner->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(211, 62, 217)',
                ], 
                [
                    'label' => 'Pelunasan Utang Purchase',
                    'data' =>   $dataPelunasan->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(201, 216, 60)',
                ],  

            ],
            'labels' => $dataPurchase->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('M')),
            'height' => '300px',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
