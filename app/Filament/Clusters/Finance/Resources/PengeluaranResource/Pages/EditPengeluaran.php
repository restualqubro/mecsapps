<?php

namespace App\Filament\Clusters\Finance\Resources\PengeluaranResource\Pages;

use App\Filament\Clusters\Finance\Resources\PengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengeluaran extends EditRecord
{
    protected static string $resource = PengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
