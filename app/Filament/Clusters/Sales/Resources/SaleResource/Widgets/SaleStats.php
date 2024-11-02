<?php

namespace App\Filament\Clusters\Sales\Resources\SaleResource\Widgets;

use App\Filament\Clusters\Sales\Resources\SaleResource\Pages\ListSales;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaleStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getStats(): array
    {
        return [
            Stat::make('Penjualan Cash', number_format(self::getSaleCash(), 0, '', '.')),
            Stat::make('Penjualan Piutang', number_format(self::getSalePiutang(), 0, '', '.')),
            Stat::make('Pelunasan Penjualan', number_format(self::getSaleLunas(), 0, '', '.')),
        ];        
    }

    protected function getTablePage(): string
    {
        return ListSales::class;
    }

    public function getSaleCash(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Cash')                        
            ->sum('total');

        return $data;
    }

    public function getSalePiutang(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Piutang')            
            ->sum('total');

        return $data;
    }

    public function getSaleLunas(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '!=', 'Cash')            
            ->sum('totalbayar');

        return $data;
    }
}
