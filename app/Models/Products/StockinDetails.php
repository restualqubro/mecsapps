<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockinDetails extends Model
{
    use HasFactory;

    protected $table = 'stockin_details';
    protected $fillable = [
        'stockin_id',
        'stock_id',
        'name',
        'qty'
    ];

    public function stock(){
        return $this->belongsTo(ProductStocks::class, 'stock_id', 'id');
    }
    
    public function stockin(){
        return $this->belongsTo(Stockins::class, 'stockin_id', 'id');
    }
}
