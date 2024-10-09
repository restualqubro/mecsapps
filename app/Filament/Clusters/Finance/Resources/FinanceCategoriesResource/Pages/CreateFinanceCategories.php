<?php

namespace App\Filament\Clusters\Finance\Resources\FinanceCategoriesResource\Pages;

use App\Filament\Clusters\Finance\Resources\FinanceCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFinanceCategories extends CreateRecord
{
    protected static string $resource = FinanceCategoriesResource::class;
}
