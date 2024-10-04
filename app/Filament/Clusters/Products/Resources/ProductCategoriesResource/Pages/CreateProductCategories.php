<?php

namespace App\Filament\Clusters\Products\Resources\ProductCategoriesResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategories extends CreateRecord
{
    protected static string $resource = ProductCategoriesResource::class;
}
