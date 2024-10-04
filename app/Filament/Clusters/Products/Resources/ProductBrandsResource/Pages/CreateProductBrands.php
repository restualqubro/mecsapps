<?php

namespace App\Filament\Clusters\Products\Resources\ProductBrandsResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductBrandsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductBrands extends CreateRecord
{
    protected static string $resource = ProductBrandsResource::class;
}
