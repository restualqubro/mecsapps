<?php

namespace App\Filament\Widgets;

use App\Models\Services\ServiceData;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ServiceDataChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $dataPending = Trend::
        query(ServiceData::where('status', '=', 'Baru')->orWhere('status', '=', 'Proses'))
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        $dataCancel = Trend::
        query(ServiceData::where('status', '=', 'Cancel'))
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        $dataSelesai = Trend::
        query(ServiceData::where('status', '=', 'Selesai'))
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [                
                [
                    'label' => 'Pending',
                    'data' =>   $dataPending->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(252, 211, 77)',
                ],
                [
                    'label' => 'Cancel',
                    'data' =>   $dataCancel->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(248, 113, 113)',
                ],
                [
                    'label' => 'Selesai',
                    'data' =>   $dataSelesai->map(fn (TrendValue $value) => $value->aggregate),                                
                    'borderColor' => 'rgb(74, 222, 128)',
                ]               
            ],
            'labels' => $dataPending->map(fn (TrendValue $value) => $value->date),
            'height' => '500px',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
