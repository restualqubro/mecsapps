<?php

namespace App\Filament\Resources\Stocks\StockoutsResource\Pages;

use App\Filament\Resources\Stocks\StockoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockouts extends ListRecords
{
    protected static string $resource = StockoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
