<?php

namespace App\Models\Services;

use App\Models\Products\ProductStocks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelesaiDetailComponents extends Model
{
    use HasFactory;

    protected $table = 'selesai_detail_components';
    protected $fillable = [
        'selesai_id',
        'stock_id',
        'component_qty',
        'hbeli'
    ];

    public function selesai(): BelongsTo
    {
        return $this->belongsTo(ServiceSelesai::class, 'selesai_id', 'id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(ProductStocks::class, 'stock_id', 'id');
    }
}
