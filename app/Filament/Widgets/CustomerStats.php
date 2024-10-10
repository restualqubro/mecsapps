<?php

namespace App\Filament\Widgets;

use App\Models\Connect\Customers;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStats extends BaseWidget
{
    protected function getStats(): array
    {
        $customerUmum = Customers::where('type', 'customer')->count();
        $customerResell = Customers::where('type', 'reseller')->count();
        $customerTwincom = Customers::where('type', 'twincom')->count();
        return [
            Stat::make('Customer Umum', $customerUmum),                
            Stat::make('Reseller', $customerResell),                
            Stat::make('Twincom', $customerTwincom),                
        ];
    }
}
