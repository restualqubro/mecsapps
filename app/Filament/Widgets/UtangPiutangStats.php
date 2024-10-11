<?php

namespace App\Filament\Widgets;

use App\Models\Transactions\Invoices;
use App\Models\Transactions\Purchase;
use App\Models\Transactions\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UtangPiutangStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Utang Pembelian', number_format(self::getPurchaseUtang(), 0, '', '.')),
            Stat::make('Piutang Service', number_format(self::getSalePiutang(), 0, '', '.')),
            Stat::make('Piutang Penjualan', number_format(self::getInvoicePiutang(), 0, '', '.')),
        ];
    }

    public static function getPurchaseUtang(): int
    {
        $data = Purchase::where('status', '=', 'Utang')
            ->get()
            ->sum('sisa');

        return $data;
    }

    public static function getSalePiutang(): int
    {
        $data = Sale::where('status', '=', 'Piutang')
            ->get()
            ->sum('sisa');

        return $data;
    }

    public static function getInvoicePiutang(): int
    {
        $data = Invoices::where('status', '=', 'Piutang')
            ->get()
            ->sum('sisa');
        
        return $data;
    }
    
}
