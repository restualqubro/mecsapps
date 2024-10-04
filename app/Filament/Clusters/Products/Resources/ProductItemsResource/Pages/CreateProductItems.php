<?php

namespace App\Filament\Clusters\Products\Resources\ProductItemsResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductItems extends CreateRecord
{
    protected static string $resource = ProductItemsResource::class;
}
