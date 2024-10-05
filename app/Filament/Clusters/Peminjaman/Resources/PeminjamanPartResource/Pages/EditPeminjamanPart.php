<?php

namespace App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource\Pages;

use App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeminjamanPart extends EditRecord
{
    protected static string $resource = PeminjamanPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
