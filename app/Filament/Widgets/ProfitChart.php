<?php

namespace App\Filament\Widgets;

use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Services\SelesaiDetailComponents;
use App\Models\Services\ServiceSelesai;
use App\Models\Services\ServiceTopartner;
use App\Models\Transactions\SaleDetails;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class ProfitChart extends ChartWidget
{
    protected static ?string $heading = 'Profit Chart';

    protected function getData(): array
    {        
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
                    query(
                        SelesaiDetailCatalogs::whereHas('selesai', function ($q)
                        {
                            $q->whereHas('invoice',function($q) {
                                $q->where('status', '!=', 'Piutang');
                            });
                            $q->whereHas('service', function($q) {            
                                $q->with('service_topartners');
                            });        
                        })                            
                        
                    )
                    ->dateColumn('selesai_detail_catalogs.created_at')
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('catalog_qty * (biaya - catalog_disc)');     
                    
        $data = self::operate();
        return [
            'datasets' => [                
                [
                    'label' => 'Profit Penjualan',
                    'data' =>   $dataSales->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(252, 211, 77)',
                ], 
                [
                    'label' => 'Profit Service',
                    // 'data' =>   $data->map(fn (TrendValue $value) => $value->aggregate),                                
                    'data' => $data['data'],
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

    public function getOmzetSum()
    {
        $data = Trend::                                         
                query(
                    SelesaiDetailCatalogs::whereHas('selesai', function ($q)
                    {
                        $q->whereHas('invoice',function($q) {
                            $q->where('status', '!=', 'Piutang');
                        });
                        $q->whereHas('service', function($q) {            
                            $q->with('service_topartners');
                        });        
                    })                            
                    
                )
                ->dateColumn('selesai_detail_catalogs.created_at')
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->sum('catalog_qty * (biaya - catalog_disc)');
                
                return $data->map(fn(TrendValue $value) =>
                $value->aggregate);
    }

    public function getComponentSum()
    {
        $data = Trend::                                         
        query(
           SelesaiDetailComponents::whereHas('selesai', function ($q)
            {
                $q->whereHas('invoice',function($q) {
                    $q->where('status', '!=', 'Piutang');
                });                
            })                            
            
        )
        ->dateColumn('selesai_detail_components.created_at')
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->sum('component_qty * hbeli');

        return $data->map(fn(TrendValue $value) =>
            $value->aggregate);
    }

    public function getTopartnerSum()
    {
        $data = Trend::                                         
        query(
            ServiceTopartner::whereHas('service', function($q) {
                $q->whereHas('selesai', function ($q) {
                    $q->whereHas('invoice', function($q) {
                        $q->where('status', '!=', 'Piutang');
                    });
                });
            })
        )
        ->dateColumn('service_topartners.created_at')
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->sum('biaya');

        return $data->map(fn(TrendValue $value) =>
            $value->aggregate);
    }

    public function operate()
    {
        $getOmzet = self::getOmzetSum();
        $getComponent = self::getComponentSum();
        $getTopartner = self::getTopartnerSum();

        $size = count(collect($getOmzet));
        $data = [];
        for($i = 0; $i < $size; $i++)
        {
            $data[] = $getOmzet[$i] - $getComponent[$i] - $getTopartner[$i];
        }
        return [
            'data' => $data
        ];
    }
}
