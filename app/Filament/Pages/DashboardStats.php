<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use App\Filament\Widgets;
use Filament\Pages\Dashboard as BaseDashboard;

class DashboardStats extends BaseDashboard
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Dashboard Statistic';

    protected static string $view = 'filament.pages.dashboard-stats';

    protected function getHeaderWidgets(): array
    {
        return [            
            Widgets\CustomerStats::class,
            Widgets\ServiceDataStats::class,
            Widgets\CustomerChart::class,
            Widgets\ServiceDataChart::class,
            // Widgets\PiutangServiceTableWidget::class,
            // Widgets\PiutangJualTableWidget::class,
            
        ];
    }
}
