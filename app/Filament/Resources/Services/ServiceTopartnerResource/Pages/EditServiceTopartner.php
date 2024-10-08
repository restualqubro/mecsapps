<?php

namespace App\Filament\Resources\Services\ServiceTopartnerResource\Pages;

use App\Filament\Resources\Services\ServiceTopartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceTopartner extends EditRecord
{
    protected static string $resource = ServiceTopartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
