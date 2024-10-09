<?php

namespace App\Filament\Clusters\Compensation\Resources;

use App\Filament\Clusters\Compensation;
use App\Filament\Clusters\Compensation\Resources\CompensationResource\Pages;
use App\Filament\Clusters\Compensation\Resources\CompensationResource\RelationManagers;
use App\Models\Finance\Compensation as Data;
use App\Models\Finance\CompensationCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompensationResource extends Resource
{
    protected static ?string $model = Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $pluralModelLabel = 'Kompensasi';

    protected static ?string $cluster = Compensation::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Kompensasi')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(CompensationCategories::all()->pluck('name', 'id')),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columns(2)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nominal'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompensation::route('/'),            
        ];
    }
}
