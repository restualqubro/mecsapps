<?php

namespace App\Filament\Widgets;

use App\Models\Finance\Pengeluaran;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CashoutChart extends ChartWidget
{
    protected static ?string $heading = 'Cashout Chart';

    protected int | string | array $columnSpan = 'full';
    
    protected function getColumns(): int
    {
        return 1;
    }

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $dataKonsumsi = Trend::query(
                            Pengeluaran::where('category_id', '=', 1)
                            ) 
                            ->dateColumn('updated_at')->between(
                                start:now()->startOfyear(),
                                end: now()->endOfyear(),
                            )
                            ->perMonth()
                            ->sum('nominal');
        $dataMitra = Trend::query(
                        Pengeluaran::where('category_id', '=', 2)
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('nominal');
        $dataListrik = Trend::query(
                        Pengeluaran::where('category_id', '=', 8)
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('nominal');  
        $dataInfaq = Trend::query(
                        Pengeluaran::where('category_id', '=', 4)
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('nominal');
        $dataOngkir = Trend::query(
                        Pengeluaran::where('category_id', '=', 9)
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('nominal');
        $dataInventaris = Trend::query(
                        Pengeluaran::where('category_id', '=', 5)
                        ) 
                        ->dateColumn('updated_at')->between(
                            start:now()->startOfyear(),
                            end: now()->endOfyear(),
                        )
                        ->perMonth()
                        ->sum('nominal');  
        $dataLainnya = Trend::query(
                        Pengeluaran::where('category_id', '=', 6)
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
                    'label' => 'Konsumsi',
                    'data' =>   $dataKonsumsi->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(61, 212, 217)',
                    'borderColor' => 'rgb(61, 212, 217)',
                ],            
                [
                    'label' => 'Mitra Mandiri',
                    'data' =>   $dataMitra->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(252, 211, 77)',
                    'borderColor' => 'rgb(252, 211, 77)',
                ],
                [
                    'label' => 'Internet, Listrik & PAM',
                    'data' =>   $dataListrik->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(219, 18, 88)',
                    'borderColor' => 'rgb(219, 18, 88)',
                ],  
                [
                    'label' => 'Sedekah, Infaq & Hibah',
                    'data' =>   $dataInfaq->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(188, 252, 13)',
                    'borderColor' => 'rgb(188, 252, 13)',
                ],  
                [
                    'label' => 'Ongkir',
                    'data' =>   $dataOngkir->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(19, 148, 49)',
                    'borderColor' => 'rgb(19, 148, 49)',
                ],
                [
                    'label' => 'Inventaris',
                    'data' =>   $dataInventaris->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(217, 137, 26)',
                    'borderColor' => 'rgb(217, 137, 26)',
                ],
                [
                    'label' => 'Lainnya',
                    'data' =>   $dataLainnya->map(fn (TrendValue $value) => $value->aggregate),                                
                    'backgroundColor' => 'rgb(120, 120, 120)',
                    'borderColor' => 'rgb(120, 120, 120)',
                ],   

            ],
            'labels' => $dataMitra->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('M')),
            'height' => '300px',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
