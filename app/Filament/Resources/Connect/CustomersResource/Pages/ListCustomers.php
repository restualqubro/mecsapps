<?php

namespace App\Filament\Resources\Connect\CustomersResource\Pages;

use App\Filament\Resources\Connect\CustomersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
