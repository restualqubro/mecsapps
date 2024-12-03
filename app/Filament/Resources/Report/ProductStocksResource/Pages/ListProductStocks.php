<?php

namespace App\Filament\Resources\Report\ProductStocksResource\Pages;

use App\Filament\Resources\Report\ProductStocksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStocksResource::class;

    protected function getHeaderActions(): array
    {
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Export PDF')
                ->url('/print/reportstock?'. $decodeQueryString)                
                ->openUrlInNewTab()            
        ];
    }
}
