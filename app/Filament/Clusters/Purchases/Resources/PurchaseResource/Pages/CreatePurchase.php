<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseResource\Pages;

use App\Filament\Clusters\Purchases\Resources\PurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {        
        $data['totalharga'] = (int)str_replace('.', '', $data['totalharga']);        
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);               
        $data['user_id'] = auth()->id();
                
        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
