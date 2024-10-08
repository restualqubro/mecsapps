<?php

namespace App\Models\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCancel extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_cancels';
    protected $fillable = 
    [
        'service_id',
        'teknisi_id',
        'alasan',
        'isKeluar'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceData::class, 'service_id', 'id');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id', 'id');
    }

}
