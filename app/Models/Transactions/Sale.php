<?php

namespace App\Models\Transactions;

use App\Models\Connect\Customers;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'sales';   
    protected $cast = [
        'is_pending'    => 'boolean'
    ];
    protected $fillable = [
        'code',
        'user_id',
        'customer_id',
        'total',
        'totaldiscount',
        'totalbayar',
        'sisa',
        'status',
        'is_pending',
        'reference',
        'description',
        'preorder_id',
        'totalpreorder'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }

    public function preorder(): BelongsTo
    {
        return $this->belongsTo(SalePreorders::class, 'preorder_id', 'id');
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }

    public function salePiutang(): HasMany
    {
        return $this->hasMany(SalePiutang::class, 'sale_id', 'id');
    }
}
