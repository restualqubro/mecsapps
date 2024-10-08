<?php

namespace App\Models\Transactions;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePiutang extends Model
{
    use HasFactory;

    protected $table = 'invoice_piutangs';
    protected $fillable = 
    [
        'invoice_id',
        'bayar',
        'user_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoices::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
