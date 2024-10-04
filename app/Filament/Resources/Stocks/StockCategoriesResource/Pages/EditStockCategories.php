<?php

namespace App\Filament\Resources\Stocks\StockCategoriesResource\Pages;

use App\Filament\Resources\Stocks\StockCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockCategories extends EditRecord
{
    protected static string $resource = StockCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
