<?php

namespace App\Filament\Resources\Services\ServiceWarrantiesResource\Pages;

use App\Filament\Resources\Services\ServiceWarrantiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceWarranties extends ListRecords
{
    protected static string $resource = ServiceWarrantiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
