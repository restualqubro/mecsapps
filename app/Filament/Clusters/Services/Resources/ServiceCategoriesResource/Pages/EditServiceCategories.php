<?php

namespace App\Filament\Clusters\Services\Resources\ServiceCategoriesResource\Pages;

use App\Filament\Clusters\Services\Resources\ServiceCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCategories extends EditRecord
{
    protected static string $resource = ServiceCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
