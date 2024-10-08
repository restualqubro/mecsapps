<?php

namespace App\Filament\Resources\Services\ServiceSelesaiResource\Pages;

use App\Filament\Resources\Services\ServiceSelesaiResource;
use Filament\Actions;
use App\Models\Services\ServiceData;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;

class EditServiceSelesai extends EditRecord
{
    protected static string $resource = ServiceSelesaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {        
        $service = ServiceData::where('id', $data['service_id'])->first();
        $teknisi = User::where('id', $data['teknisi_id'])->first();
        $data['service_id'] = $data['service_id'];
        $data['teknisi'] = $teknisi->name;
        $data['service_id'] = $service->code;
        $data['reference'] = $data['reference'];
        $data['name'] = $service->customer->name;
        $data['merk'] = $service->merk;
        $data['seri'] = $service->seri;
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {                
        $data['subtotal_service'] = (int)str_replace('.', '', $data['subtotal_service']);
        $data['totaldiscount_service'] = (int)str_replace('.', '', $data['totaldiscount_service']);
        $data['subtotal_component'] = (int)str_replace('.', '', $data['subtotal_component']);
        $data['total'] = (int)str_replace('.', '', $data['total']);                

        return $data;
    }
}
