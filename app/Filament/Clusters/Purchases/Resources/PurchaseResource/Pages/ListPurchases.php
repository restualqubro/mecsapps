<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseResource\Pages;

use App\Filament\Clusters\Purchases\Resources\PurchaseResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListPurchases extends ListRecords
{ 
    use ExposesTableToWidgets;

    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PurchaseResource\Widgets\PurchaseStats::class,
        ];
    }
}
