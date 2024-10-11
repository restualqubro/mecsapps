<?php

namespace App\Filament\Widgets;

use App\Models\Finance\BankTransfers;
use App\Models\Finance\Penarikan;
use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Transactions\SaleDetails;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class BalanceStats extends BaseWidget
{
    protected function getStats(): array
    {       
        $getPenarikan = self::getPenarikanCash() - self::getPenarikanRekening(); 
        $getSaldoCash = self::getOmzetSales() + self::getOmzetInvoices() - self::getTransferOut() - self::getPenarikanCash();
        $getSaldoMandiri = self::getTransferIn() - self::getTransferOut() - self::getPenarikanRekening();
        return [
            Stat::make('Saldo Cash', number_format(($getSaldoCash), 0, '', '.')),
            Stat::make('Saldo Mandiri', number_format($getSaldoMandiri, 0, '', '.')),            
            Stat::make('Penarikan Tunai', number_format($getPenarikan, 0, '', '.')),            
        ];
    }

    public static function getOmzetSales(): int
    {
        $data = SaleDetails::whereHas('sale', function ($q) {
            $q->where('is_pending', '=', 0);
            $q->where('status', '!=', 'Piutang');                    
        })->sum(DB::raw('qty * (hjual - disc)'));

        return $data;
    }

    public static function getOmzetInvoices(): int
    {
        $data = SelesaiDetailCatalogs::whereHas('selesai', function($q) {
            $q->whereHas('invoice', function($q) {
                $q->where('status', '!=', 'Piutang');
            });
        })->sum(DB::raw('catalog_qty * (biaya - catalog_disc)'));

        return $data;
    }

    public static function getTransferIn(): int
    {
        $data =  BankTransfers::where('type', '=', 'Masuk')->sum('nominal');

        return $data;
    }

    public static function getTransferOut(): int
    {
        $data =  BankTransfers::where('type', '=', 'Keluar')->sum('nominal');

        return $data;
    }

    public static function getPenarikanCash(): int
    {
        $data = Penarikan::where('sumber', 'Cash')
                ->where('status', '=', 'Approve')->sum('nominal');

        return $data;
    }

    public static function getPenarikanRekening(): int
    {
        $data = Penarikan::where('sumber', 'Rekening')
                ->where('status', '=', 'Approve')->sum('nominal');

        return $data;
    }
}
