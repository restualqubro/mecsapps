<?php

namespace App\Filament\Resources\Transactions\InvoiceResource\Widgets;

use App\Filament\Resources\Transactions\InvoiceResource\Pages\ListInvoices;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvoiceStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getStats(): array
    {
        return [
            Stat::make('Invoice Cash', number_format(self::getInvoiceCash(), 0, '', '.')),
            Stat::make('Penjualan Piutang', number_format(self::getInvoicePiutang(), 0, '', '.')),
            Stat::make('Pelunasan Invoice', number_format(self::getInvoiceLunas(), 0, '', '.')),
        ];        
    }

    protected function getTablePage(): string
    {
        return ListInvoices::class;
    }

    public function getInvoiceCash(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Cash')                        
            ->sum('totalbayar');

        return $data;
    }

    public function getInvoicePiutang(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '=', 'Piutang')            
            ->sum('sisa');

        return $data;
    }

    public function getInvoiceLunas(): int
    {
        $data = $this->getPageTableQuery()
            ->where('status', '!=', 'Cash')            
            ->sum('totalbayar');

        return $data;
    }
}
