<?php

namespace App\Filament\Resources\Services\ServiceDataResource\Pages;

use App\Filament\Resources\Services\ServiceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceData extends EditRecord
{
    protected static string $resource = ServiceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
