<?php

namespace App\Filament\Resources\Retur\InvoiceReturResource\Pages;

use App\Filament\Resources\Retur\InvoiceReturResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInvoiceRetur extends CreateRecord
{
    protected static string $resource = InvoiceReturResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['totalbiaya'] = (int)(str_replace('.', '', $data['totalbiaya']));
        $data['user_id'] = Auth::id();

        return $data;
    }    
}
