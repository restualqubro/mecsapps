<?php

namespace App\Filament\Widgets;

use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Transactions\SaleDetails;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ProfitChart extends ChartWidget
{
    protected static ?string $heading = 'Profit Chart';

    protected function getData(): array
    {

        // Country::join('state', 'state.country_id', '=', 'country.country_id')
        // ->join('city', 'city.state_id', '=', 'state.state_id')
        // ->get(['country.country_name', 'state.state_name', 'city.city_name'])
        $dataSales = Trend::
                    query(SaleDetails::join('sales', 'sale_details.sale_id', '=', 'sales.id')
                                        ->join('product_stocks', 'sale_details.stock_id', '=', 'product_stocks.id')
                                        ->where('status', '!=', 'Piutang'))                        
                    ->dateColumn('sale_details.created_at')
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('qty * (hjual - hbeli)');
            $dataInvoices = Trend::
                    query(SelesaiDetailCatalogs::join('service_selesais', 'selesai_detail_catalogs.selesai_id', '=', 'service_selesais.id'))                                        
                                        // ->where('invoices.status', '!=', 'Piutang'))                        
                    ->dateColumn('selesai_detail_catalogs.created_at')
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('catalog_qty * (selesai_detail_catalogs.biaya)');
                    
        // $dataInvoices = Trend::
        //             query(SelesaiDetailCatalogs::whereHas('selesai', function($q) {
        //                 $q->whereHas('invoice', function($q) {
        //                     $q->where('status', '!=', 'Piutang');
        //                 });
        //             }))
        //             ->between(
        //                 start: now()->startOfYear(),
        //                 end: now()->endOfYear(),
        //             )
        //             ->perMonth()
        //             ->sum('catalog_qty * (biaya - catalog_disc)');

        return [
            'datasets' => [                
                [
                    'label' => 'Profit Penjualan',
                    'data' =>   $dataSales->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(252, 211, 77)',
                ], 
                [
                    'label' => 'Profit Service',
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
        return 'bar';
    }
}
