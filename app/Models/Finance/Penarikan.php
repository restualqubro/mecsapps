<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penarikan extends Model
{
    use HasFactory;

    protected $table = 'penarikans';
    protected $fillable = 
    [
        'sumber',
        'nominal',
        'status',
        'submitted_id',
        'approval_id'
    ];

    public function submitted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_id', 'id');
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_id', 'id');
    }
}
