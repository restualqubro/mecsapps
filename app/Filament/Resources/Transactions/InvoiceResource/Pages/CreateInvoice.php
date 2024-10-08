<?php

namespace App\Filament\Resources\Transactions\InvoiceResource\Pages;

use App\Filament\Resources\Transactions\InvoiceResource;
use App\Models\Services\ServiceData;
use Filament\Actions;
use App\Models\Services\ServiceSelesai;
use App\Models\Services\ServiceLog;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $selesai = ServiceSelesai::where('id', $data['selesai_id'])->first();
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);
        $data['totalbayar'] = $data['totalbayar'];        
        if ($data['sisa'] > 0 )
        {
            $data['status'] = 'PIUTANG';
        } else 
        {
            $data['status'] = 'CASH';
        }               
        ServiceLog::create([
            'service_id'    => $selesai->service->id,
            'status'        => 'Keluar',
            'description'   => 'Unit Telah selesai proses service, Sudah diambil oleh Customer',
            'user_id'       => Auth::id()
        ]);
        ServiceData::where('id', $selesai->service->id)->update(['status' => 'Keluar']);                  
        return $data;        
    }    

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
