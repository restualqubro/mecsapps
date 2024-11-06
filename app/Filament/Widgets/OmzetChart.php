<?php

namespace App\Filament\Widgets;

use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Transactions\Invoices;
use App\Models\Transactions\Sale;
use App\Models\Transactions\SaleDetails;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class OmzetChart extends ChartWidget
{
    protected static ?string $heading = 'Omzet Chart';

    protected function getData(): array
    {        
        $dataSales = Trend::model(Sale::class) 
                        ->dateColumn('updated_at')
                        ->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('totalbayar');
        $dataInvoices = Trend::model(Invoices::class)
                        ->dateColumn('updated_at')
                        ->between(
                            start:now()->startOfYear(),
                            end: now()->endOfYear(),
                        )
                        ->perMonth()
                        ->sum('totalbayar');       

        return [
            'datasets' => [                
                [
                    'label' => 'Omzet Penjualan',
                    'data' =>   $dataSales->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(252, 211, 77)',
                ], 
                [
                    'label' => 'Omzet Service',
                    'data' =>   $dataInvoices->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(248, 113, 113)',
                ],                
            ],
            'labels' => $dataSales->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('M')),
            'height' => '500px',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
