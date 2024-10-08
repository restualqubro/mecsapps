<?php

namespace App\Filament\Resources\Finance\BankTransferResource\Pages;

use App\Filament\Resources\Finance\BankTransferResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankTransfer extends CreateRecord
{
    protected static string $resource = BankTransferResource::class;
}
