<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\ProductStocksResource\Pages;
use App\Models\Products\ProductCategories;
use App\Models\Products\ProductStocks;
use Filament\Resources\Resource;
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
                Tables\Columns\TextColumn::make('stok'),
                Tables\Columns\TextColumn::make('hbeli')
                    ->numeric(decimalPlaces:0),
                Tables\Columns\TextColumn::make('item.hjual')
                    ->numeric(decimalPlaces:0)
            ])            
            
            ->filters([
                Tables\Filters\SelectFilter::make('item')                                        
                    ->label('by Category')
                    ->options(
                        ProductCategories::all()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->multiple()
                    // ->modifyQueryUsing(function (Builder $query, $state)
                    // {
                    //     if (! $state['values']) {
                    //         return $query;
                    //     }
                    //     // return $query->whereHas('businesses', fn($query) => $query->where('id', $state['value']));
                    //     return $query->whereHas('item', fn($query) => 
                    //         $query->orWhere('category_id', 'LIKE', '%'.$state['values'].'%')
                    //     );                    
                    // })
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