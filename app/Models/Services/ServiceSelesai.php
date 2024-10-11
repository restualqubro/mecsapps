<?php

namespace App\Models\Services;

use App\Models\Transactions\Sale;
use App\Models\Transactions\Invoices;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceSelesai extends Model
{
    use HasFactory, HasUlids;

    protected $table  = 'service_selesais';

    protected $fillable = [        
        'service_id',
        'teknisi_id',
        'subtotal_service',
        'totaldiscount_service',
        'subtotal_component',
        'total', 
        'reference'
    ];

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceData::class, 'service_id', 'id');
    }    

    public function detailComponent(): HasMany
    {
        return $this->hasMany(SelesaiDetailComponents::class, 'selesai_id', 'id');
    }

    public function detailService(): HasMany
    {
        return $this->HasMany(SelesaiDetailCatalogs::class, 'selesai_id', 'id');
    }
    
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'reference', 'id');
    }
    
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoices::class, 'selesai_id', 'id');
    }
}
