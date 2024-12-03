<?php

namespace App\Http\Controllers\Print;

use App\Filament\Widgets\BalanceStats;
use App\Models\Transactions\Sale;
use App\Http\Controllers\Controller;
use App\Models\Finance\Compensation;
use App\Models\Finance\Pemasukan;
use App\Models\Finance\Penarikan;
use App\Models\Finance\Pengeluaran;
use App\Models\Products\StockoutDetails;
use App\Models\Report\MonthlyReport as ReportMonthlyReport;
use App\Models\Retur\InvoiceRetur;
use App\Models\Services\ServiceTopartner;
use App\Models\Transactions\Invoices;
use App\Models\Transactions\Purchase;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Filament\Forms\Get;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MonthlyReport extends Controller
{
    public function print(GeneralSettings $settings, $id) 
    {                    
        $date = ReportMonthlyReport::find($id);                
        $sales = Sale::where('updated_at', '>=', $date->date_from)
                        ->where('updated_at', '<=', $date->date_to);
        $invoice = Invoices::where('updated_at', '>=', $date->date_from)
                            ->where('updated_at', '<=', $date->date_to);        
        $purchase = Purchase::where('updated_at', '>=', $date->date_from)
                            ->where('updated_at', '<=', $date->date_to)
                            ->get();
        $pemasukan = Pemasukan::where('updated_at', '>=', $date->date_from)
                                ->where('updated_at', '<=', $date->date_to)
                                ->sum('nominal');
        $toPartner = ServiceTopartner::where('updated_at', '>=', $date->date_from)
                                ->where('updated_at', '<=', $date->date_to)
                                ->where('status_pembayaran', 'Lunas')
                                ->sum('biaya');
        $compensation = Compensation::where('updated_at', '>=', $date->date_from)
                                ->where('updated_at', '<=', $date->date_to)
                                ->sum('nominal');
        $kerugian = StockoutDetails::whereHas('stockout', fn ($q) =>  (
                                        $q->where('category_id', 2)
                                    ))
                                    ->whereHas('stock')
                                    ->where('updated_at', '>=', $date->date_from)
                                    ->where('updated_at', '<=', $date->date_to)                                                                                                            
                                    ->get()
                                    ->sum('sum');
        $cashout = Pengeluaran::where('created_at', '>=', $date->date_from)
                                ->where('created_at', '<=', $date->date_to)
                                ->whereHas('category', fn($q) => 
                                    (
                                        $q->where('name', 'NOT LIKE', '%DLL')
                                    ));    
        $penarikan = Penarikan::where('created_at', '>=', $date->date_from)
                                ->where('created_at', '<=', $date->date_to)                                 
                                ->sum('nominal');
        $returService = InvoiceRetur::where('created_at', '>=', $date->date_from)
                                    ->where('created_at', '<=', $date->date_to)  
                                    ->sum('totalbiaya');
        $data = [
            "title"             => "Monthly Report",
            "logo"              => Storage::url($settings->brand_logo),
            "dateTime"          => Carbon::now(),
            "sales"             => $sales->where('status', 'Cash')->sum('totalbayar'),
            "invoice"           => $invoice->where('status', 'Cash')->sum('totalbayar'),
            "pemasukan"         => $pemasukan,
            "pelunasanSales"    => $sales->where('status', 'Lunas')->sum('totalbayar'),
            "pelunasanInvoices" => $invoice->where('status', 'Lunas')->sum('totalbayar'),
            "purchase"          => $purchase->where('status', 'Cash')->sum('totalbayar'),
            "pelunasanPurchase" => $purchase->where('status', 'Lunas')->sum('totalbayar '),
            "topartner"         => $toPartner, 
            "compensation"      => $compensation,
            "kerugian"          => $kerugian,
            "allcashout"        => $cashout->sum('nominal'),
            "penarikan"         => $penarikan,
            "returService"      => $returService,
            "saldoCash"         => BalanceStats::getSaldoCash(),
            "saldoMandiri"      => BalanceStats::getSaldoMandiri()
        ];

        // return $data;
        return View('print.monthlyreport', $data);          
    }
}
