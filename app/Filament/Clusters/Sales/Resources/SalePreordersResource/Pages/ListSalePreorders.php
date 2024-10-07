<?php

namespace App\Filament\Clusters\Sales\Resources\SalePreordersResource\Pages;

use App\Filament\Clusters\Sales\Resources\SalePreordersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalePreorders extends ListRecords
{
    protected static string $resource = SalePreordersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
