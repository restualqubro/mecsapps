<?php

namespace App\Filament\Clusters\Sales\Resources\SalePreordersResource\Pages;

use App\Filament\Clusters\Sales\Resources\SalePreordersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalePreorders extends CreateRecord
{
    protected static string $resource = SalePreordersResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {                      
        $data['user_id'] = auth()->id();
                
        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
