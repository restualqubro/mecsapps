<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory, HasUlids;
    
    protected $table = 'monthly_report';
    // protected $primaryKey = 'id';
    // public $incrementing = false;
    protected $fillable = [
        'year',
        'month',
        'date_from',
        'date_to',
        'description'
    ];
}
