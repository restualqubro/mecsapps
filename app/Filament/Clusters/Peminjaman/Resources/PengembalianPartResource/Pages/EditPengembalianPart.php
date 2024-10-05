<?php

namespace App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource\Pages;

use App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengembalianPart extends EditRecord
{
    protected static string $resource = PengembalianPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
