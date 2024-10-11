<?php

namespace App\Models\Transactions;

use App\Models\Products\ProductStocks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetails extends Model
{
    use HasFactory;

    protected $table = 'sale_details';
    protected $fillable = [
        'sale_id',
        'stock_id',
        'qty', 
        'hjual',
        'disc',
        'profit'
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function productStocks(): BelongsTo
    {
        return $this->belongsTo(ProductStocks::class, 'stock_id', 'id');
    }

    public function getJumlahAttribute()
    {
        return ($this->qty * ($this->hjual - $this->disc));
    }

    public function getSumAttribute()
    {
        $getHbeli = ProductStocks::find($this->stock_id);
        return ($this->qty * ($this->hjual - $getHbeli->hbeli));
    }
}
