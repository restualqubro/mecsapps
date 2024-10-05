<?php

namespace App\Models\Products;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengembalianPart extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_parts';

    protected $fillable = [
        'submitted_id',
        'approval_id', 
        'status', 
        'peminjaman_id',
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

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(PeminjamanPart::class, 'peminjaman_id', 'id');
    }
}
