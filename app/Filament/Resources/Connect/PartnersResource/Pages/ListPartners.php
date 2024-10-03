<?php

namespace App\Filament\Resources\Connect\PartnersResource\Pages;

use App\Filament\Resources\Connect\PartnersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartners extends ListRecords
{
    protected static string $resource = PartnersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
