<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stockouts extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'stockouts';
    protected $fillable = [
        'code',        
        'category_id',
        'description',
        'user_id',        
    ];

    public function detailStockout(): HasMany
    {
        return $this->hasMany(StockoutDetails::class, 'stockout_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StockCategories::class);
    }    
}
