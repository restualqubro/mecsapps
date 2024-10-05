<?php

namespace App\Filament\Resources\Connect\PartnersResource\Pages;

use App\Filament\Resources\Connect\PartnersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePartners extends CreateRecord
{
    protected static string $resource = PartnersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
