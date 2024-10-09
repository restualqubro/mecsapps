<?php

namespace App\Filament\Clusters\Compensation\Resources\CompensationCategoriesResource\Pages;

use App\Filament\Clusters\Compensation\Resources\CompensationCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompensationCategories extends ListRecords
{
    protected static string $resource = CompensationCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
