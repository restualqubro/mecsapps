<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\BalanceStats;

class OmzetStats extends BaseWidget
{
    protected function getStats(): array
    {        
        return [
            Stat::make('Omzet Service', number_format(BalanceStats::getOmzetInvoices() , 0, '', '.')),
            Stat::make('Omzet Penjualan',  number_format(BalanceStats::getOmzetSales(), 0, '', '.')),
            Stat::make('Total Omzet',  number_format((BalanceStats::getOmzetInvoices() + BalanceStats::getOmzetSales()), 0, '', '.')),
        ];
    }   

}
