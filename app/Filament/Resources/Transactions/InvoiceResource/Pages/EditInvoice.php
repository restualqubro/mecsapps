<?php

namespace App\Filament\Resources\Transactions\InvoiceResource\Pages;

use App\Filament\Resources\Transactions\InvoiceResource;
use App\Models\Services\ServiceSelesai;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $selesai = ServiceSelesai::find($data['selesai_id']);
        $data['customer_name'] = $selesai->service->customer->name;
        $data['subtotal_products'] = number_format($selesai->subtotal_products, 0, '', '.');
        $data['totaldiscount_products'] = number_format($selesai->totaldiscount_products, 0, '', '.');
        $data['subtotal_service'] = number_format($selesai->subtotal_service, 0, '', '.');
        $data['totaldiscount_service'] = number_format($selesai->totaldiscount_service, 0, '', '.');
        $data['total'] = number_format($selesai->total, 0, '', '.');
        $data['service_id'] = $data['id'];
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
