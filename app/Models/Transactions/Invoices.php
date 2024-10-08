<?php

namespace App\Models\Transactions;

use App\Models\Services\ServiceSelesai;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoices extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'invoices';
    protected $fillable = 
    [
        'code', 
        'selesai_id',
        'subtotal',
        'totaldiscount',
        'total',
        'totalbayar',
        'sisa',
        'status',
        'description'
    ];

    public function selesai(): BelongsTo
    {
        return $this->belongsTo(ServiceSelesai::class, 'selesai_id', 'id');
    }
}
