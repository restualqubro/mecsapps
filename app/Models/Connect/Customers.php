<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'customers';
    protected $fillable = [
        'code',
        'name',
        'telp',
        'address',
        'type'
    ];   
}
