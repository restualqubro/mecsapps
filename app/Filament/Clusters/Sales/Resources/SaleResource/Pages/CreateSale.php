<?php

namespace App\Filament\Clusters\Sales\Resources\SaleResource\Pages;

use App\Filament\Clusters\Sales\Resources\SaleResource;
use Filament\Actions;
use App\Models\Transactions\SalePreorders;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {           
        $data['total'] = (int)str_replace('.', '', $data['total']);
        $data['totaldiscount'] = (int)str_replace('.', '', $data['totaldiscount']);
        $data['totalbayar'] = (int)str_replace('.', '', $data['totalbayar']);
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);        
        $data['user_id'] = auth()->id();
        if ($data['preorder_id']) {
            $data['totalpreorder'] = (int)str_replace('.', '', $data['totaldp']);
            SalePreorders::where('id', $data['preorder_id'])->update([
                'status'    => 'Selesai'
            ]);
        } else {
            $data['totalpreorder'] = 0;
        }                   
                
        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
