<?php

namespace App\Filament\Clusters\Sales\Resources\SaleResource\Pages;

use App\Filament\Clusters\Sales\Resources\SaleResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SaleResource\Widgets\SaleStats::class,
        ];
    }
}
