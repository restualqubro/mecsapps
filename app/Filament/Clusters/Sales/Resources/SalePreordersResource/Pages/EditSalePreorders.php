<?php

namespace App\Filament\Clusters\Sales\Resources\SalePreordersResource\Pages;

use App\Filament\Clusters\Sales\Resources\SalePreordersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalePreorders extends EditRecord
{
    protected static string $resource = SalePreordersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
