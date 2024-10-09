<?php

namespace App\Models\Retur;

use App\Models\Services\SelesaiDetailCatalogs;
use App\Models\Services\ServiceCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceReturDetails extends Model
{
    use HasFactory;
    
    protected $table = 'invoice_retur_details';
    protected $fillable = [
        'returinvoice_id',
        'selesaidetailcatalog_id',
        'qty',
        'biaya',
        'disc'
    ];

    public function retur(): BelongsTo
    {
        return $this->belongsTo(InvoiceRetur::class, 'returinvoice_id', 'id');
    }

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(SelesaiDetailCatalogs::class, 'selesaidetailcatalog_id', 'id');
    }
}
