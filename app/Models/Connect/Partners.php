<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    use HasFactory;

    protected $table = 'partners';
    protected $fillable = [
        'name',
        'telp',
        'address'
    ];
}
