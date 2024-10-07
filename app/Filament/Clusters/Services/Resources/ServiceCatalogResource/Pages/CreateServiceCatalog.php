<?php

namespace App\Filament\Clusters\Services\Resources\ServiceCatalogResource\Pages;

use App\Filament\Clusters\Services\Resources\ServiceCatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceCatalog extends CreateRecord
{
    protected static string $resource = ServiceCatalogResource::class;
}
