<?php

namespace App\Filament\Widgets;

use App\Models\Finance\Penarikan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BalanceStats extends BaseWidget
{
    protected function getStats(): array
    {       
        $getPenarikan = self::getPenarikanCash() - self::getPenarikanRekening(); 
        return [
            // Stat::make('Saldo Cash', number_format(($getOmzetJual + $getServiceSum - $getTransferSum - $getPenarikanCash), 0, '', '.')),
            // Stat::make('Saldo Mandiri', number_format($getTransferSum - $getPenarikanRekening, 0, '', '.')),            
            Stat::make('Penarikan Tunai', number_format($getPenarikan, 0, '', '.')),            
        ];
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
