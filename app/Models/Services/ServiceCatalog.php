<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCatalog extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_catalogs';
    protected $fillable = [
        'name',
        'biaya_min',
        'biaya_max',
        'warranty'
    ];
}
