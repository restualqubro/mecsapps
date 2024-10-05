<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockoutDetails extends Model
{
    use HasFactory;

    protected $table = 'stockout_details';
    protected $fillable = [
        'stockout_id',
        'stock_id',
        'name',
        'qty'
    ];

    public function stock(){
        return $this->belongsTo(ProductStocks::class, 'stock_id', 'id');
    }
    
    public function stockout(){
        return $this->belongsTo(Stockouts::class, 'stockout_id', 'id');
    }
}
