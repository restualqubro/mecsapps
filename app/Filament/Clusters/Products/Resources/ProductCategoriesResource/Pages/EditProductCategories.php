<?php

namespace App\Filament\Clusters\Products\Resources\ProductCategoriesResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductCategories extends EditRecord
{
    protected static string $resource = ProductCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
