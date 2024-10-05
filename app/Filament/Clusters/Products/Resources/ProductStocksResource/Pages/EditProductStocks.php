<?php

namespace App\Filament\Clusters\Products\Resources\ProductStocksResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductStocksResource;
use App\Models\Products\ProductStocks;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductStocks extends EditRecord
{
    protected static string $resource = ProductStocksResource::class;        
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
