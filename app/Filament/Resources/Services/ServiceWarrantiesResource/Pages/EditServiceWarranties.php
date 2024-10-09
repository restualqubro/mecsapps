<?php

namespace App\Filament\Resources\Services\ServiceWarrantiesResource\Pages;

use App\Filament\Resources\Services\ServiceWarrantiesResource;
use App\Models\Transactions\Invoices;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceWarranties extends EditRecord
{
    protected static string $resource = ServiceWarrantiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $invoice = Invoices::find($data['invoice_id'])->first();
        if ($invoice)
        {
            $data['name'] = $invoice->selesai->service->customer->name;
            $data['merk'] = $invoice->selesai->service->merk;
            $data['seri'] = $invoice->selesai->service->seri;
        }

        return $data;
    }
}
