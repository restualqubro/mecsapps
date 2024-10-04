<?php

namespace App\Filament\Resources\Stocks;

use App\Filament\Resources\Stocks\StockCategoriesResource\Pages;
use App\Models\Products\StockCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockCategoriesResource extends Resource
{
    protected static ?string $model = StockCategories::class;

    protected static ?string $navigationGroup = 'Stocks';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required(),
                Forms\Components\Select::make('jenis')
                    ->options([
                        'Stockin'    => 'STOCKIN',
                        'Stockout'   => 'STOCKOUT'
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('jenis')
                    ->label('Jenis Stok')
                    ->colors([
                        'primary'   => "Stockin",
                        'danger'   => "Stockout",
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
   

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockCategories::route('/'),
        ];
    }
}
