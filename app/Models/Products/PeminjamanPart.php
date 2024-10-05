<?php

namespace App\Models\Products;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanPart extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_parts';

    protected $fillable = [
        'submitted_id',
        'approval_id', 
        'status', 
        'stock_id', 
        'qty',
        'description'
    ];

    public function submitted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_id', 'id');
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_id', 'id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(ProductStocks::class, 'stock_id', 'id');
    }
}
