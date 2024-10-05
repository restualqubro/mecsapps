<?php

namespace App\Filament\Resources\Stocks\StockoutsResource\Pages;

use App\Filament\Resources\Stocks\StockoutsResource;
use App\Models\Products\StockoutDetails;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditStockouts extends EditRecord
{
    protected static string $resource = StockoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Stockins')
                ->modalDescription('You sure want deleted this data ? if you delete it, 
                                    item details will also deleted')   
                ->action(function($record) {
                    StockoutDetails::where('stockout_id', $record->id)->delete();
                    $record->delete();                    
                })
                ->after(function () {
                    redirect()->route('filament.admin.resources.stocks.stockouts.index');
                    Notification::make()
                        ->title('Deleted successfully')
                        ->icon('heroicon-o-check-circle')
                        ->iconColor('success')
                        ->send();
                }),
        ];
    }
}
