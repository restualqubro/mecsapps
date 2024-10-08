<?php

namespace App\Filament\Resources\Finance\BankAccountResource\Pages;

use App\Filament\Resources\Finance\BankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankAccount extends CreateRecord
{
    protected static string $resource = BankAccountResource::class;
}
