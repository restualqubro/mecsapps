<?php

namespace App\Filament\Resources\Retur\InvoiceReturResource\Pages;

use App\Filament\Resources\Retur\InvoiceReturResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceRetur extends EditRecord
{
    protected static string $resource = InvoiceReturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
