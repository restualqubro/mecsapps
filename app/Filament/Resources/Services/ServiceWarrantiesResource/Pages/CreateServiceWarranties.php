<?php

namespace App\Filament\Resources\Services\ServiceWarrantiesResource\Pages;

use App\Filament\Resources\Services\ServiceWarrantiesResource;
use App\Models\Services\ServiceLog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Transactions\Invoices;
use Illuminate\Support\Facades\Auth;

class CreateServiceWarranties extends CreateRecord
{
    protected static string $resource = ServiceWarrantiesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $invoice = Invoices::find($data['invoice_id'])->first();
        $data['service_id'] = $invoice->selesai->service->id;
        $data['status'] = 'Baru';
        $data['user_id'] = auth()->id();
        $data['description'] = 'Garansi : Unit Garansi Service';
        
        ServiceLog::create($data);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
