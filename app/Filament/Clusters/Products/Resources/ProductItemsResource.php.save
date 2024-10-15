<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\ProductItemsResource\Pages;
use App\Models\Products\ProductBrands;
use App\Models\Products\ProductCategories;
use App\Models\Products\ProductItems;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class ProductItemsResource extends Resource
{
    protected static ?string $model = ProductItems::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Products::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Code Generator')
                    ->schema(                        
                        [                        
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->required()
                            ->options(ProductCategories::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('brand_id')
                            ->label('Brand')
                            ->required()                    
                            ->options(ProductBrands::all()->pluck('name', 'id'))
                            ->searchable(),                                                    
                        Forms\Components\TextInput::make('code')
                        ->label('Kode Product')
                        ->required()
                        ->readOnly(),
                    ])->columns('3')
                    ->footerActions([
                        Forms\Components\Actions\Action::make('Generate')
                        ->action(fn (Forms\Get $get, Forms\Set $set) => self::generateCode() )
                        ->hidden(fn(string $operation): bool => $operation === 'view') 
                        
                    ])->hidden(fn(string $operation):bool => $operation === 'edit'),                      
                    Forms\Components\Section::make('Product Details')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Product')
                                ->required(),
                            Forms\Components\Select::make('kondisi')
                                ->label('Kondisi')
                                ->required()
                                ->options([
                                    'BARU'  => 'BARU',
                                    'SECOND'=>  'SECOND',
                                ]),                                                   
                            Forms\Components\TextInput::make('sale_warranty')
                                ->label('Garansi Customer')
                                ->required()
                                ->numeric(),                       
                            Forms\Components\TextInput::make('hress')
                                ->label('Harga Resell')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('hjual')
                                ->label('Harga Jual Umum')
                                ->numeric()
                                ->required(),                           
                            Forms\Components\Textarea::make('description')
                                ->label('Keterangan/Description'),
                            Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                ->label('Photo Product')
                                ->collection('products')
                                ->multiple()
                                ->columnSpan('full')                            
                        ])->columns([
                            'sm' => 3,
                            'xl' => 3,
                            '2xl' => 3,
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Item')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Item')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categories')
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brands')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sum')
                    ->label('Stok'),                                                    
                Tables\Columns\TextColumn::make('hjual')
                    ->label('Harga Umum')  
                    ->money('IDR')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categories')
                    ->options(ProductCategories::all()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('brand_id')
                    ->label('Brands')
                    ->options(ProductBrands::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),                        
                ])                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([                
                TextEntry::make('code')
                    ->label('Kode Item')
                    ->weight(FontWeight::Bold),
                TextEntry::make('name')
                    ->label('Name'),
                TextEntry::make('sum')                   
                    ->label('Stok'),
                TextEntry::make('sumpinjam')
                    ->label('Peminjaman Item'),                                 
                TextEntry::make('category.name')
                    ->label('Categories'),
                TextEntry::make('brand.name')
                    ->label('Brands'),
                TextEntry::make('kondisi')
                    ->label('Kondisi'),
                TextEntry::make('hjual')
                    ->label('Harga Jual Umum')
                    ->money('IDR'),
                TextEntry::make('hress')
                    ->label('Harga Jual Reseller')
                    ->money('IDR'),     
                TextEntry::make('description')
                    ->label('Description / Specification')
                    ->columnSpan('full'),                                   
                                    
            ])->columns([
                'sm'    => 1,
                'lg'    => 2,
                'xl'    => 2
            ]);
    }

    public static function generateCode(Forms\Get $get, Forms\Set $set): void
    {
        $category = ProductCategories::find($get('category_id'));                            
        $brand = ProductBrands::find($get('brand_id'));                                                        
        
        if ($category === null || $brand === null) {
            $set('code', "Generate Gagal!!");
        } 
        else {  
            $last = ProductItems::where([
                ['category_id', '=', $category->id],
                ['brand_id', '=', $brand->id]
            ])->max('code');
            if ($last != null) {                                    
                $tmp = substr($last, 7, 3)+1;
                $set('code', $category->init."-".$brand->init.sprintf("%03s", $tmp));
            } else {
                $set('code', $category->init."-".$brand->init."001");
            }                
        }
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductItems::route('/'),
        ];
    }
}
