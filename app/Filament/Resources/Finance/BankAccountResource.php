<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\BankAccountResource\Pages;
use App\Filament\Resources\Finance\BankAccountResource\RelationManagers;
use App\Models\Finance\BankAccounts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccounts::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?String $navigationGroup = 'Finances';

    protected static ?string $pluralModelLabel = 'Bank Account';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bank_name')
                    ->label('Nama Bank')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->label('Nomor Rekening')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Nama Bank'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor Rekening')                    
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankAccounts::route('/'),
        ];
    }
}
