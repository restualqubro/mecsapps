<?php

namespace App\Filament\Resources\Stocks\StockinsResource\Pages;

use App\Filament\Resources\Stocks\StockinsResource;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Actions\Modal\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditStockins extends EditRecord
{
    protected static string $resource = StockinsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Stockins')
                ->modalDescription('You sure want deleted this data ? if you delete it, 
                                    you must update product stock manually')                                          
        ];
    }
}
