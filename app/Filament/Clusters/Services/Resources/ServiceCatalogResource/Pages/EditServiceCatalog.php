<?php

namespace App\Filament\Clusters\Services\Resources\ServiceCatalogResource\Pages;

use App\Filament\Clusters\Services\Resources\ServiceCatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCatalog extends EditRecord
{
    protected static string $resource = ServiceCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
