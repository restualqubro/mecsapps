<?php

namespace App\Filament\Clusters\Compensation\Resources\CompensationResource\Pages;

use App\Filament\Clusters\Compensation\Resources\CompensationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompensation extends ListRecords
{
    protected static string $resource = CompensationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
