<?php

namespace App\Models\Transactions;

use App\Models\Connect\Customers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePreorders extends Model
{
    use HasFactory;

    protected $table = 'sale_preorders';
    protected $fillable = [
        'code',
        'customer_id',
        'user_id',
        'nominal',
        'description',
        'estimasi',
        'status'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
