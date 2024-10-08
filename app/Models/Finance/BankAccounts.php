<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccounts extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';
    protected $fillable = [
        'bank_name',
        'number',
        'name'
    ];
}
