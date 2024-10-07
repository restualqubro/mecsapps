<?php

namespace App\Filament\Clusters\Services\Resources\ServiceCategoriesResource\Pages;

use App\Filament\Clusters\Services\Resources\ServiceCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceCategories extends ListRecords
{
    protected static string $resource = ServiceCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
