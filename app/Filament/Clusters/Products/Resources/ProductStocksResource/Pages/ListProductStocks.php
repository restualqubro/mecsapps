<?php

namespace App\Filament\Clusters\Products\Resources\ProductStocksResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductStocksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStocksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
