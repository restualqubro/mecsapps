<?php

namespace App\Filament\Clusters\Finance\Resources\PenarikanResource\Pages;

use App\Filament\Clusters\Finance\Resources\PenarikanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenarikans extends ListRecords
{
    protected static string $resource = PenarikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
