<?php

namespace App\Models\Retur;

use App\Models\Transactions\Invoices;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceRetur extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'invoice_returs';
    protected $fillable = [
        'code', 
        'invoice_id',
        'user_id',
        'totalbiaya',
        'description'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(InvoiceReturDetails::class, 'returinvoice_id', 'id');
    }
}
