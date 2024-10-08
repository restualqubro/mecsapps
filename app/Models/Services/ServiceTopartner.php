<?php

namespace App\Models\Services;

use App\Models\Connect\Partners;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTopartner extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_topartners';
    protected $fillable = [
        'service_id',
        'partner_id',
        'status',
        'update',
        'biaya',
        'status_pembayaran'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceData::class, 'service_id', 'id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'partner_id', 'id');
    }
}
