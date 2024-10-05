<?php

namespace App\Filament\Resources\Stocks\StockoutsResource\Pages;

use App\Filament\Resources\Stocks\StockoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStockouts extends CreateRecord
{
    protected static string $resource = StockoutsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
