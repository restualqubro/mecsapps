<?php

namespace App\Filament\Resources\Connect;

use App\Filament\Resources\Connect\SuppliersResource\Pages;
use App\Models\Connect\Suppliers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuppliersResource extends Resource
{
    protected static ?string $model = Suppliers::class;

    protected static ?string $navigationGroup = 'Connects';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([             
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('telp')
                    ->label('No HP/WA')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->prefix('+62'),                            
                Forms\Components\Textarea::make('address')                    
                    ->label('Alamat')                         
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Supplier')
                    ->searchable()
                    ->sortable(),                
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Supplier')
                    ->limit(40)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('contact')
                        ->hiddenLabel()
                        ->tooltip('Contact')
                        ->url(function($record) {
                            
                            // return dd($record);
                            return 'https://wa.me/+62'.$record->telp;
                        })
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->openUrlInNewTab(),
                    Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),
                    Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                    Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete'),
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
            'index' => Pages\ListSuppliers::route('/'),            
        ];
    }
}
