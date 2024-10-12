<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelesaiDetailCatalogs extends Model
{
    use HasFactory;

    protected $table = 'selesai_detail_catalogs';
    protected $fillable = [
        'selesai_id',
        'servicecatalog_id',
        'catalog_qty',
        'biaya',
        'catalog_disc'
    ];

    public function selesai(): BelongsTo
    {
        return $this->belongsTo(ServiceSelesai::class, 'selesai_id', 'id');
    }

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalog::class, 'servicecatalog_id', 'id');
    }

    public function getSumAttribute()
    {
        $omzet = $this->catalog_qty * ($this->biaya * $this->catalog_disc);
    }
}
