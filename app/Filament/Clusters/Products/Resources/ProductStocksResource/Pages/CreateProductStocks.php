<?php

namespace App\Filament\Clusters\Products\Resources\ProductStocksResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductStocksResource;
use App\Models\Products\ProductStocks;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductStocks extends CreateRecord
{
    protected static string $resource = ProductStocksResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $stock = ProductStocks::where('item_id', $data['item_id'])->max('code');
        if ($stock != null) 
        {                                                                                            
            $tmp = substr($stock, 1, 3)+1;
            $code = sprintf("%03s", $tmp);                                                                            
        } else {
            $code =  "001";
        }

        $data['code'] = $code;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
