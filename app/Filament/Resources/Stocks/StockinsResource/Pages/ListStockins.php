<?php

namespace App\Filament\Resources\Stocks\StockinsResource\Pages;

use App\Filament\Resources\Stocks\StockinsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockins extends ListRecords
{
    protected static string $resource = StockinsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
