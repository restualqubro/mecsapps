<?php

namespace App\Filament\Resources\Stocks\StockCategoriesResource\Pages;

use App\Filament\Resources\Stocks\StockCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockCategories extends ListRecords
{
    protected static string $resource = StockCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
