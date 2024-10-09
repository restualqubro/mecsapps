<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCategories extends Model
{
    use HasFactory;

    protected $table = 'finance_categories';
    protected $fillable = [
        'name',
        'jenis'
    ];
}
