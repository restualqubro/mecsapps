<?php

namespace App\Filament\Clusters\Finance\Resources;

use App\Filament\Clusters\Finance;
use App\Filament\Clusters\Finance\Resources\FinanceCategoriesResource\Pages;
use App\Filament\Clusters\Finance\Resources\FinanceCategoriesResource\RelationManagers;
use App\Models\Finance\FinanceCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinanceCategoriesResource extends Resource
{
    protected static ?string $model = FinanceCategories::class;

    protected static ?string $pluralModelLabel = 'Categories';

    protected static ?string $slug = 'finance-categories';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Finance::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required(),
                Forms\Components\Select::make('jenis')
                    ->label('Jenis Kategori')
                    ->options([
                        'Pemasukan' => 'Pemasukan',
                        'Pengeluaran'   => 'Pengeluaran'
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Kategori')
                    ->badge()
                    ->colors([
                        'warning' => 'Pengeluaran',
                        'success'   => 'Pemasukan'
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinanceCategories::route('/'),            
        ];
    }
}
