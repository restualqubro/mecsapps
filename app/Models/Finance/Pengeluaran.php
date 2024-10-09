<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable =
    [
        'category_id',
        'nominal',
        'description',
        'status',
        'submitted_id',
        'approval_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategories::class, 'category_id', 'id');
    }

    public function submitted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_id', 'id');
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_id', 'id');
    }
}
