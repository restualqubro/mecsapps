<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'telp',
        'address'
    ];
}
