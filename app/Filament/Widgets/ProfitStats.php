<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\BalanceStats;
use App\Models\Services\SelesaiDetailComponents;
use App\Models\Services\ServiceTopartner;
use App\Models\Transactions\SaleDetails;
use Illuminate\Support\Facades\DB;

class ProfitStats extends BaseWidget
{
    protected function getStats(): array
    {
        $getInvoiceProfit = BalanceStats::getOmzetInvoices() - self::getComponentSum() - self::getTopartnerSum();
        $getProfitTotals = $getInvoiceProfit + self::getSalesProfit() - (BalanceStats::getPenarikanCash() + BalanceStats::getPenarikanRekening());
        return [
            Stat::make('Profit Service', number_format($getInvoiceProfit , 0, '', '.')),
            Stat::make('Profit Penjualan',  number_format(self::getSalesProfit(), 0, '', '.')),
            Stat::make('Total Profit',  number_format($getProfitTotals, 0, '', '.')),
        ];
    }

    public function getComponentSum(): int
    {
        $data = SelesaiDetailComponents::whereHas('selesai', function($q) {
            $q->whereHas('invoice', function($q) {
                $q->where('status', '!=', 'Piutang');
            });            
        })->sum(DB::raw('component_qty * hbeli'));

        return $data;
    }     
    
    public function getTopartnerSum(): int
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

    public function getSalesProfit(): int
    {
        $data = SaleDetails::whereHas('sale', function ($q) {
                $q->where('is_pending', '=', 0);
                $q->where('status', '!=', 'Piutang');
            })
            ->get()
            ->sum('sum');
        
        return $data;
    }
}
