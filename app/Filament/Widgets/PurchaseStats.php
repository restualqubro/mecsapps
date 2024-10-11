<?php

namespace App\Filament\Widgets;

use App\Models\Finance\Compensation;
use App\Models\Products\StockoutDetails;
use App\Models\Transactions\Purchase;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PurchaseStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pembelian Cash', number_format(self::getPurchase(), 0, '', '.')),
            Stat::make('Kompensasi', number_format(self::getCompensation(), 0, '', '.')),
            Stat::make('Kerugian', number_format(self::getKerugian(), 0, '', '.')),
        ];
    }

    public static function getPurchase(): int
    {
        $data = Purchase::where('status', '=', 'Cash')
            ->get()
            ->sum('totalharga');

        return $data;
    }

    public static function getCompensation(): int
    {
        $data = Compensation::get()->sum('nominal');

        return $data;
    }

    public static function getKerugian(): int
    {
        $data = StockoutDetails::whereHas('stockout', function($q) {
            $q->whereHas('category', function($q) {
                $q->where('name', 'LIKE', '%Rusak / Damage%');
            });
        })
        ->get()
        ->sum('sum');

        return $data;
    }
}
