<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Stockins extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'stockins';
    protected $fillable = [
        'code',        
        'category_id',
        'description',
        'user_id',
        'sumber'
    ];

    public function detailStockin(): HasMany
    {
        return $this->hasMany(StockinDetails::class, 'stockin_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StockCategories::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($project) {
    //         if (empty($project->user_id)) {
    //             $project->user_id = Auth::id();
    //         }
    //     });
    // }
}
