<?php

namespace App\Filament\Resources\Connect\SuppliersResource\Pages;

use App\Filament\Resources\Connect\SuppliersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuppliers extends CreateRecord
{
    protected static string $resource = SuppliersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
