<?php

namespace App\Filament\Resources\Connect\SuppliersResource\Pages;

use App\Filament\Resources\Connect\SuppliersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuppliers extends EditRecord
{
    protected static string $resource = SuppliersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
