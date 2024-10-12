<?php

namespace App\Filament\Widgets;

use App\Models\Transactions\InvoicePiutang;
use App\Models\Transactions\PurchaseUtang;
use App\Models\Transactions\SalePiutang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PelunasanStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pelunasan Piutang Jual', number_format(self::getSalesPiutang(), 0, '', '.')),
            Stat::make('Pelunasan Piutang Service', number_format(self::getInvoicesPiutang(), 0, '', '.')),
            Stat::make('Pelunasan Utang Beli', number_format(self::getPurchasesUtang(), 0, '', '.')),
        ];
    }

    public static function getSalesPiutang(): int
    {
        $data = SalePiutang::
            get()
            ->sum('bayar');

        return $data;
    }

    public static function getInvoicesPiutang(): int
    {
        $data = InvoicePiutang::
            get()
            ->sum('bayar');

        return $data;
    }

    public static function getPurchasesUtang(): int
    {
        $data = PurchaseUtang::
            get()
            ->sum('bayar');

        return $data;
    }
}
