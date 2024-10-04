<?php

namespace App\Filament\Clusters\Products\Resources\ProductBrandsResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductBrandsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductBrands extends EditRecord
{
    protected static string $resource = ProductBrandsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
