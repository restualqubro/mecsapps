<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets;

class DashboardFinances extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Dashboard Finance';

    protected static string $view = 'filament.pages.dashboard-finances';

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\BalanceStats::class,
            // Widgets\OmzetChart::class,                        
            // Widgets\ProfitChart::class,                        
        ];
    }
}
