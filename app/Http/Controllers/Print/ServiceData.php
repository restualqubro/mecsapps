<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Services\ServiceData as Data;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceData extends Controller
{
    public function print(GeneralSettings $settings) 
    {
        $status = request('tableFilters.status.value');
        if (!$status) 
        {
            $items = Data::orderBy('id', 'desc')->get();        
        }  else {
            $items = Data::where('status', $status)->orderBy('id', 'desc')->get();
        }
        $data = [
            'title'     => 'SERVICE DATA',
            'logo'      => Storage::url($settings->brand_logo),
            'item'      => $items,
            'count'     => $items->count(),
            'dateTime'  => \Carbon\Carbon::now(),
            'users'     => auth()->user()->name,
        ];
    	return view('print.servicedata', $data);
    }
}
