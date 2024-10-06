<?php

namespace App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource\Pages;

use App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListPengembalianParts extends ListRecords
{
    protected static string $resource = PengembalianPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {                                
                    $data['submitted_id'] = Auth::id();
                    return $data;
                }),
        ];
    }
}
