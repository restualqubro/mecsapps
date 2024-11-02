<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseResource\Widgets;

use App\Filament\Clusters\Purchases\Resources\PurchaseResource\Pages\ListPurchases;
use App\Models\Transactions\Purchase;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PurchaseStats extends BaseWidget
{    
    use InteractsWithPageTable;

    protected function getStats(): array
    {
        return [
            Stat::make('Pembelian Cash', number_format(self::getPurchase(), 0, '', '.')),
            Stat::make('Pembelian Utang', number_format(self::getPurchaseUtang(), 0, '', '.')),
            Stat::make('Pembelian Lunas', number_format(self::getPurchaseLunas(), 0, '', '.')),
        ];        
    }

    protected function getTablePage(): string
    {
        return ListPurchases::class;
    }

    public function getPurchase(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Cash')            
            ->get()
            ->sum('totalharga');

        return $data;
    }

    public function getPurchaseUtang(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Utang')
            ->get()
            ->sum('totalharga');

        return $data;
    }

    public function getPurchaseLunas(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Lunas')
            ->get()
            ->sum('totalharga');

        return $data;
    }
}
