<?php

namespace App\Filament\Widgets;

use App\Models\Connect\Customers;
use Filament\Widgets\ChartWidget;

class CustomerWidgets extends ChartWidget
{
    protected static ?string $heading = 'Customer Chart Data';

    // protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return [
            'labels' => [
                'Umum',
                'Reseller',
                'Twincom'
            ],            
            'datasets'=> [
                [
                  'label' => 'My First Dataset',
                  'data'=> [
                    Customers::where('type', 'Customer')->count(),
                    Customers::where('type', 'Reseller')->count(),
                    Customers::where('type', 'Twincom')->count(),
                  ],
                  'backgroundColor'=> [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                  ],
                  'hoverOffset' => 4
                
                ],            
            ],
        ];    
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
