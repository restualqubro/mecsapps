<?php

namespace App\Filament\Clusters\Finance\Resources\FinanceCategoriesResource\Pages;

use App\Filament\Clusters\Finance\Resources\FinanceCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinanceCategories extends ListRecords
{
    protected static string $resource = FinanceCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
