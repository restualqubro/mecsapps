<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategories extends Model
{
    use HasFactory;
    
    protected $table = 'service_categories';
    protected $fillable = [
        'name'
    ];
}
