<?php

namespace App\Models\Transactions;

use App\Models\Products\ProductStocks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetails extends Model
{
    use HasFactory;

    protected $table = 'purchase_details';
    protected $fillable = [
        'purchase_id',
        'stock_id',
        'qty',
        'hbeli',
        'supplier_warranty'
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function productStocks(): BelongsTo
    {
        return $this->belongsTo(ProductStocks::class, 'stock_id', 'id');
    }
}
