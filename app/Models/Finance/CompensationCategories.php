<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensationCategories extends Model
{
    use HasFactory;

    protected $table = 'compensation_categories';
    protected $fillable = [
        'name'
    ];
}
