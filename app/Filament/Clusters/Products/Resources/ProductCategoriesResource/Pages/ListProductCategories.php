<?php

namespace App\Filament\Clusters\Products\Resources\ProductCategoriesResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
