<?php

namespace App\Filament\Resources\Stocks\StockCategoriesResource\Pages;

use App\Filament\Resources\Stocks\StockCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockCategories extends CreateRecord
{
    protected static string $resource = StockCategoriesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
