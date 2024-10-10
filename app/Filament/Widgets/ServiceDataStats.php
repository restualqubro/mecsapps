<?php

namespace App\Filament\Widgets;

use App\Models\Services\ServiceData;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceDataStats extends BaseWidget
{
    protected function getStats(): array
    {
        $servicePending = ServiceData::where('status', '=', 'Baru')->orWhere('status', '=', 'Proses')->count();
        $serviceCancel = ServiceData::where('status', 'Cancel')->count();
        $serviceSelesai = ServiceData::where('status', 'Selesai')->where('status', 'Selesai')->count();

        return [
            Stat::make('Service Pending', $servicePending),                
            Stat::make('Service Cancel', $serviceCancel),                
            Stat::make('Service Selesai', $serviceSelesai),                
        ];
    }  
}
