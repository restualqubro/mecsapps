<?php

namespace App\Filament\Resources\Services\ServiceSelesaiResource\Pages;

use App\Filament\Resources\Services\ServiceSelesaiResource;
use App\Models\Services\ServiceLog;
use App\Models\Services\ServiceData;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceSelesai extends CreateRecord
{
    protected static string $resource = ServiceSelesaiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['teknisi_id'] = auth()->user()->id;        
        $data['subtotal_service'] = (int)str_replace('.', '', $data['subtotal_service']);
        $data['totaldiscount_service'] = (int)str_replace('.', '', $data['totaldiscount_service']);
        $data['subtotal_component'] = (int)str_replace('.', '', $data['subtotal_component']);
        $data['total'] = (int)str_replace('.', '', $data['total']);
        
        ServiceLog::create([
            'service_id'    => $data['service_id'],
            'status'        => 'Selesai',
            'description'   => 'Unit Telah selesai proses service, siap untuk di ambil customer',
            'user_id'       => auth()->user()->id
        ]);
        ServiceData::where('id', $data['service_id'])->update(['status' => 'Selesai']);

        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
