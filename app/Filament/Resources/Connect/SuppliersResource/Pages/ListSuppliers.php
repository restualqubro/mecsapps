<?php

namespace App\Filament\Resources\Connect\SuppliersResource\Pages;

use App\Filament\Resources\Connect\SuppliersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SuppliersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
