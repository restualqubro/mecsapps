<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductItems extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory, HasUlids;

    protected $table = 'product_items';
    protected $fillable = [
        'code',
        'name', 
        'category_id',
        'brand_id',
        'kondisi',
        'hress',
        'hjual',
        'sale_warranty'
    ];

    public function getFilamentMediaUrl(): ?string
    {
        return $this->getMedia('products')?->first()?->getUrl() ?? $this->getMedia('products')?->first()?->getUrl('thumb') ?? null;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();
    }

    public function stock(): HasMany
    {
        return $this->HasMany(ProductStocks::class, 'item_id', 'id');
    }  

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategories::class, 'category_id', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrands::class, 'brand_id', 'id');
    }

    public function getSumpinjamAttribute()
    {           
        //$getStockId = ProductStocks::where('item_id', $this->id)->first()->pluck('id');
        //$getPeminjaman = PeminjamanPart::where('stock_id', '=', $getStockId)
	//				->where('status', '=', 'Approve')->sum('qty');
        //return $getPeminjaman;
	//return dd($getStock);

	//$getStockId = ProductStocks::where('item_id', $this->id)->first()->pluck('id');
        //$getPeminjaman = PeminjamanPart::where('status', 'Approve')
	//				->where('stock_id', $getStockId)
          //                              ->sum('qty');
        //return $getPeminjaman;
    }
    
    public function getSumAttribute()
    {
        //$sumPinjam = $this->getSumpinjamAttribute();
        $sum = 0;
        $get = ProductStocks::select('stok')->where('item_id', $this->id)->get();                
        foreach($get as $stok)
        {
            $sum = $sum + $stok->stok;
        }
        return $sum;
    }     
}
