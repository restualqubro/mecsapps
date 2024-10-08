<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Services\ServiceSelesai;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SelesaiReceipt extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $items = ServiceSelesai::find($id);
        $data = [
            'title'     => 'SELESAI SERVICE RECEIPT',
            'items'     => $items,
            'datas'     => SelesaiDetailCatalogs::where('selesai_id', $id)->get(),
            'logo'      => Storage::url($settings->brand_logo),            
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            // 'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];    	
    	return View('print.selesaireceipt', $data);
    }
}
