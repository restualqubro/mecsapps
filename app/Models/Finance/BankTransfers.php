<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransfers extends Model
{
    use HasFactory;
    
    protected $table = 'bank_transfers';
    protected $fillable = [
        'nominal',
        'type',
        'account_id',
        'description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccounts::class, 'account_id', 'id');
    }
}
