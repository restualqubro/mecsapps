<?php

namespace App\Models\Services;

use App\Models\Connect\Customers;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceData extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_data';
    protected $fillable = 
    [
        'code', 
        'customer_id',
        'category_id',
        'merk',
        'seri',
        'sn',
        'reference',
        'kelengkapan',
        'keluhan',
        'description', 
        'status',
        'penawaran',
        'penawaran_details'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategories::class, 'category_id', 'id');
    }

    public function serviceLog(): HasMany
    {
        return $this->hasMany(ServiceLog::class, 'service_id', 'id');
    }

    public function selesai(): HasOne
    {
        return $this->hasOne(ServiceSelesai::class, 'service_id', 'id');
    }
}
