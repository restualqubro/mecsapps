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

    protected static ?string $slug = 'service-catalogue';

    protected static ?string $pluralModelLabel = 'Service Catalog';

    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Services::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Katalog Service')
                            ->required(),
                        Forms\Components\TextInput::make('warranty')
                            ->label('Garansi Service')
                            ->numeric()
                            ->required(),
                    ])->columns(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('biaya_min')
                            ->label('Biaya Minimal')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('biaya_max')
                            ->label('Biaya Maximal')
                            ->numeric()
                            ->required(),                        
                    ])->columns(2),                                              
            ])->columns('Full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('biaya_min')
                    ->money('IDR'), 
                Tables\Columns\TextColumn::make('biaya_max')
                    ->money('IDR'),                
                Tables\Columns\TextColumn::make('warranty')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListServiceCatalogs::route('/'),            
        ];
    }
}
