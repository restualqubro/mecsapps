<?php

namespace App\Filament\Clusters\Products\Resources\ProductBrandsResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductBrandsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductBrands extends ListRecords
{
    protected static string $resource = ProductBrandsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
