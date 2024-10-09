<?php

namespace App\Models\Services;

use App\Models\Transactions\Invoices;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceWarranty extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_warranties';
    protected $fillable = [
        'code',
        'invoice_id', 
        'kelengkapan',
        'keluhan',
        'status',
        'update',
        'user_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
