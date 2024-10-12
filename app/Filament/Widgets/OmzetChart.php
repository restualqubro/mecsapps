<?php

namespace App\Filament\Widgets;

use App\Models\Services\SelesaiDetailCatalogs;
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
        $dataSales = Trend::
                    query(SaleDetails::whereHas('sale', function ($q) {
                            $q->where('is_pending', '=', 0);
                            $q->where('status', '!=', 'Piutang');                    
                        }))
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('qty * (hjual - disc)');
                    
        $dataInvoices = Trend::
                    query(SelesaiDetailCatalogs::whereHas('selesai', function($q) {
                        $q->whereHas('invoice', function($q) {
                            $q->where('status', '!=', 'Piutang');
                        });
                    }))
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('catalog_qty * (biaya - catalog_disc)');

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
