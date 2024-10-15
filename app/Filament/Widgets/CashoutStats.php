<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\BalanceStats;
use App\Models\Finance\Pengeluaran;

class CashoutStats extends BaseWidget
{
    protected function getStats(): array
    {        
        return [
            Stat::make('Cashout : Konsumsi', number_format((self::getCashoutKonsumsi()), 0, '', '.')),
            Stat::make('Cashout : Infaq / Sedekah', number_format((self::getCashoutInfaq()), 0, '', '.')),
            Stat::make('Cashout : Mitra Mandiri', number_format((self::getCashoutMitra()), 0, '', '.')),
            Stat::make('Cashout : Listrik, Internet & PAM', number_format((self::getCashoutListrik()), 0, '', '.')),
            Stat::make('Cashout : Ongkir', number_format((self::getCashoutOngkir()), 0, '', '.')),
            Stat::make('Cashout : Lainnya', number_format((self::getCashoutLainnya()), 0, '', '.')),

        ];

    }

    public static function getCashoutKonsumsi(): int
    {
        $data = Pengeluaran::whereHas('category', function($q) {
            $q->where('name', '=', 'Konsumsi');
        })
        ->where('status', '=', 'Approve')
        ->get()
        ->sum('nominal');

        return $data;
    }

    public static function getCashoutInfaq(): int
    {
        $data = Pengeluaran::whereHas('category', function($q) {
            $q->where('name', 'LIKE', '%Infaq%');
        })
        ->where('status', '=', 'Approve')
        ->get()
        ->sum('nominal');

        return $data;
    }    

    public static function getCashoutMitra(): int
    {
        $data = Pengeluaran::whereHas('category', function ($q) {
            $q->where('name', 'LIKE', '%Mitra%');
        })
        ->where('status', '=', 'Approve')
        ->get()
        ->sum('nominal');

        return $data;
    }

    public static function getCashoutListrik(): int
    {
        $data = Pengeluaran::whereHas('category', function ($q) {
            $q->where('name', 'LIKE', '%Listrik%');
        })
        ->where('status', '=', 'Approve')
        ->get()
        ->sum('nominal');

        return $data;
    }

    public static function getCashoutOngkir(): int
    {
        $data = Pengeluaran::whereHas('category', function ($q) {
            $q->where('name', '=', 'Ongkir');
        })
        ->where('status', '=', 'Approve')
        ->get()
        ->sum('nominal');

        return $data;
    }  
    
    public static function getCashoutLainnya(): int
    {
        $data = Pengeluaran::where('status', '=', 'Approve')
                            ->where('category_id', '!=', '7')
        ->get()
        ->sum('nominal');
        $sum = $data - self::getCashoutKonsumsi() - self::getCashoutInfaq() - self::getCashoutListrik() 
                    - self::getCashoutMitra() - self::getCashoutOngkir();
        return $sum;
    }  

    public static function getAllCashouts(): int
    {
        $data = Pengeluaran::where('status', '=', 'Approve')
                ->get()
                ->sum('nominal');

        return $data;
    }
}
