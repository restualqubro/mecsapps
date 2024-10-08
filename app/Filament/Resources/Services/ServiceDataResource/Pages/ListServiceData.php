<?php

namespace App\Filament\Resources\Services\ServiceDataResource\Pages;

use App\Filament\Resources\Services\ServiceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServiceData extends ListRecords
{
    protected static string $resource = ServiceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Umum' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('customer', function($q) {
                    $q->where('type', '=', 'Customer');
                    $q->where('name', '!=', 'PTAM');
                })),
            'Reseller' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('customer', function($q) {
                    $q->where('type', '=', 'Reseller');
                })),
            'Twincom' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('customer', function($q) {
                    $q->where('type', '=', 'Twincom');
                })),
            'Ptam' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('customer', function($q) {
                    $q->where('name', 'LIKE', '%Air Minum%');
                })),
            
        ];
    }
}
