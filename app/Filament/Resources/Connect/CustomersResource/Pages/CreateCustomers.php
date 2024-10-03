<?php

namespace App\Filament\Resources\Connect\CustomersResource\Pages;

use App\Filament\Resources\Connect\CustomersResource;
use App\Models\Connect\Customers;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomers extends CreateRecord
{
    protected static string $resource = CustomersResource::class;    
}
