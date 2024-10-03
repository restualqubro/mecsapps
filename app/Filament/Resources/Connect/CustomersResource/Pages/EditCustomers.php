<?php

namespace App\Filament\Resources\Connect\CustomersResource\Pages;

use App\Filament\Resources\Connect\CustomersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomers extends EditRecord
{
    protected static string $resource = CustomersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
