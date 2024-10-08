<?php

namespace App\Filament\Resources\Services\ServiceCancelResource\Pages;

use App\Filament\Resources\Services\ServiceCancelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCancel extends EditRecord
{
    protected static string $resource = ServiceCancelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
