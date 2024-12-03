<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\ProductStocksResource\Pages;
use App\Models\Products\ProductBrands;
use App\Models\Products\ProductCategories;
use App\Models\Products\ProductStocks;
use Filament\Resources\Resource;
use Tapp\FilamentValueRangeFilter\Filters\ValueRangeFilter;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductStocksResource extends Resource
{
    protected static ?string $model = ProductStocks::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Report';    

    protected static ?string $pluralModelLabel = 'Product Stocks';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fullCode'),
                Tables\Columns\TextColumn::make('item.name'),
                Tables\Columns\TextColumn::make('item.category.name'),
                Tables\Columns\TextColumn::make('item.brand.name'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok'),
                Tables\Columns\TextColumn::make('hbeli')
                    ->label('Harga Beli')
                    ->numeric(decimalPlaces:0),
                Tables\Columns\TextColumn::make('item.hjual')
                    ->label('Harga Jual')
                    ->numeric(decimalPlaces:0)
            ])            
            
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')                                        
                    ->relationship('item.category', 'name')
                    ->label('by Category')
                    ->searchable()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('brand_id')
                    ->relationship('item.brand', 'name')
                    ->label('by Brand')
                    ->searchable()
                    ->multiple(),     
                ValueRangeFilter::make('stok')                          
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            );            
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductStocks::route('/'),            
        ];
    }
}    