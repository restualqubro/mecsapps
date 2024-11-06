<?php

namespace App\Filament\Widgets;

use App\Models\Finance\BankTransfers;
use App\Models\Finance\Penarikan;
use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Transactions\Invoices;
use App\Models\Transactions\SaleDetails;
use App\Models\Transactions\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\PelunasanStats;
use App\Filament\Widgets\CashoutStats;
use App\Filament\Widgets\PurchaseStats;
use App\Filament\Widgets\ProfitStats;
use App\Models\Finance\Compensation;
use App\Models\Finance\Pemasukan;
use App\Models\Finance\Pengeluaran;
use App\Models\Retur\InvoiceRetur;
use App\Models\Services\ServiceTopartner;
use App\Models\Transactions\Purchase;
use Illuminate\Support\Facades\DB;

class BalanceStats extends BaseWidget
{   

    protected int | string | array $columnSpan = 'full';
    
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {       
        $getPenarikan = self::getPenarikanCash() + self::getPenarikanRekening(); 
        $getSaldoMandiri = self::getTransferIn() - (self::getTransferOut() + self::getPenarikanRekening());
        $getSaldoCash = (self::getOmzetSales() + self::getOmzetInvoices() + self::getPemasukan()) 
                        - (self::getPenarikanCash() + self::getAllCashouts() + self::getPurchase() 
                        + self::getCompensation() + self::getTopartnerSum() + 
                        + $getSaldoMandiri) + self::getReturServiceSum();        
        return [
            Stat::make('Saldo Cash', number_format(($getSaldoCash), 0, '', '.')),
            Stat::make('Saldo Mandiri', number_format($getSaldoMandiri, 0, '', '.')),            
            // Stat::make('Penarikan Tunai', number_format($getPenarikan, 0, '', '.')),            
        ];
    }

    public static function getOmzetSales(): int
    {
        $data = Sale::get()->sum('totalbayar');

        return $data;
    }

    public static function getOmzetInvoices(): int
    {
        $data = Invoices::get()->sum('totalbayar');

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

    public static function getPemasukan(): int
    {
        $data = Pemasukan::get()->sum('nominal');

        return $data;
    }

    public static function getAllCashouts(): int
    {
        $data = Pengeluaran::where('status', '=', 'Approve')
                ->get()
                ->sum('nominal');

        return $data;
    }

    public static function getPurchase(): int
    {
        $data = Purchase::where('status', '=', 'Cash')
            ->get()
            ->sum('totalbayar');

        return $data;
    }

    public static function getCompensation(): int
    {
        $data = Compensation::get()->sum('nominal');

        return $data;
    }

    public static function getTopartnerSum(): int
    {
        $data = ServiceTopartner::whereHas('service', function($q) {
                    $q->whereHas('selesai', function ($q) {
                        $q->whereHas('invoice', function($q) {
                            $q->where('status', '!=', 'Piutang');
                        });
                    });
                })
                ->get()
                ->sum('biaya');

        return $data;
    }

    public static function getReturServiceSum(): int
    {
        $data = InvoiceRetur::get()
                ->sum('totalbiaya');

        return $data;
    }
}