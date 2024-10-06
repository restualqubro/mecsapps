<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\ProductStocksResource\Pages;
use App\Models\Products\ProductItems;
use App\Models\Products\ProductStocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class ProductStocksResource extends Resource
{
    protected static ?string $model = ProductStocks::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Products::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Code Generator')
                    ->schema(                        
                        [                                      
                        Forms\Components\Select::make('item_id')
                            ->label('Kode Items')
                            ->required()
                            ->options(fn() => self::getProductItems())                            
                            ->searchable()
                            ->hiddenOn('edit'),                                                           
                        Forms\Components\TextInput::make('hbeli')
                            ->label('Harga Beli')
                            ->numeric()
                            ->required(),
                            Forms\Components\TextInput::make('stok')
                            ->label('Stok Awal')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('supplier_warranty')
                            ->label('Garansi Supplier')
                            ->required()
                            ->numeric(),                                                                       
                    ])->columns('2')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.code')
                    ->label('Kode Items')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama Items')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Stok'),                                    
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok'),                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ]) 
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getProductItems(): Collection
    {
        $stock = ProductStocks::get(); 
        return $stock->mapWithKeys(function (ProductStocks $stock) {
            return [$stock->id => sprintf('%s-%s | %s', $stock->item->code, $stock->code, $stock->item->name)];
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductStocks::route('/'),
            'create' => Pages\CreateProductStocks::route('/create'),
            'edit' => Pages\EditProductStocks::route('/{record}/edit'),
        ];
    }
}
