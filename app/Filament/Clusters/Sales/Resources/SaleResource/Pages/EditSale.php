<?php

namespace App\Filament\Clusters\Sales\Resources\SaleResource\Pages;

use App\Filament\Clusters\Sales\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
	$data['subtotal'] = $data['total'] + $data['totaldiscount'];	
	return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
	$data['total'] = (int)str_replace('.', '', $data['total']);
        $data['totaldiscount'] = (int)str_replace('.', '', $data['totaldiscount']);
        $data['totalbayar'] = (int)str_replace('.', '', $data['totalbayar']);
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);

	if ($data['sisa'] === 0) {
		$data['status'] = 'Cash';
	} else {
		$data['status'] = 'Piutang';
	}
	return $data;
    }
}
