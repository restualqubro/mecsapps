<?php

namespace App\Filament\Clusters\Products\Resources\ProductItemsResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductItems extends ListRecords
{
    protected static string $resource = ProductItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
