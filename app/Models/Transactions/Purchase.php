<?php

namespace App\Models\Transactions;

use App\Models\Connect\Suppliers;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'purchases';
    protected $increment = FALSE;
    protected $fillable = [
        'code', 
        'user_id', 
        'supplier_id',
        'totalharga',
        'totalbayar',
        'sisa',
        'description',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(PurchaseDetails::class);
    }
}
