<?php

namespace App\Filament\Resources\Stocks\StockinsResource\Pages;

use App\Filament\Resources\Stocks\StockinsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStockins extends CreateRecord
{
    protected static string $resource = StockinsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
