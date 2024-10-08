<?php

namespace App\Filament\Resources\Services\ServiceTopartnerResource\Pages;

use App\Filament\Resources\Services\ServiceTopartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceTopartners extends ListRecords
{
    protected static string $resource = ServiceTopartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
