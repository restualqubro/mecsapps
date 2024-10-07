<?php

namespace App\Filament\Clusters\Services\Resources;

use App\Filament\Clusters\Services;
use App\Filament\Clusters\Services\Resources\ServiceCatalogResource\Pages;
use App\Filament\Clusters\Services\Resources\ServiceCatalogResource\RelationManagers;
use App\Models\Services\ServiceCatalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;

class ServiceCatalogResource extends Resource
{
    protected static ?string $model = ServiceCatalog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Services::class;

    protected static ?int $navigationSort = 2;  

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListServiceCatalogs::route('/'),
            'create' => Pages\CreateServiceCatalog::route('/create'),
            'edit' => Pages\EditServiceCatalog::route('/{record}/edit'),
        ];
    }
}
