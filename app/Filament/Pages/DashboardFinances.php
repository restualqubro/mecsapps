<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;

class DashboardFinances extends Page
{
    
    use HasPageShield, InteractsWithForms;    

    public function getColumns(): int | string | array
    {
        return 2;
    }
    
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Dashboard Finance';

    protected static string $view = 'filament.pages.dashboard-finances';       

    protected function getHeaderWidgets(): array
    {
        return [  
            // Widgets\FinanceFilters::class,                   
            Widgets\BalanceStats::class,
            Widgets\OmzetChart::class,
            Widgets\ProfitChart::class,
            Widgets\OmzetStats::class,
            Widgets\ProfitStats::class,
            // Widgets\PelunasanStats::class,
            // Widgets\PurchaseStats::class,
            // Widgets\UtangPiutangStats::class,
            // Widgets\CashoutStats::class,                                                
            // Widgets\ProfitChart::class,                        
        ];
    }
}
