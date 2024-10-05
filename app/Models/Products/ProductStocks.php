<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStocks extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'product_stocks';
    protected $fillable = [
        'code',
        'item_id',
        'supplier_warranty',
        'hbeli',
        'stok',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(ProductItems::class, 'item_id', 'id');
    }   
    
    public function getFullcodeAttribute()
    {
        return "{$this->item->code}-{$this->code}";
    }    
}
