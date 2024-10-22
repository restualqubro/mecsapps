<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\BankTransferResource\Pages;
use App\Filament\Resources\Finance\BankTransferResource\RelationManagers;
use App\Models\Finance\BankTransfers;
use App\Models\Finance\BankAccounts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankTransferResource extends Resource
{
    protected static ?string $model = BankTransfers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Finances';

    protected static ?string $pluralModelLabel = 'Bank Transfer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal') 
                    ->label('Nominal Transfer')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->required()
                    ->options([
                        'masuk' => 'Masuk',
                        'keluar'=> 'Keluar',
                    ]),
                Forms\Components\Select::make('account_id')
                    ->label('Bank Account')
                    ->options(BankAccounts::all()->pluck('bank_name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success'   => 'masuk',
                        'danger'    => 'keluar'
                    ]),
                Tables\Columns\TextColumn::make('nominal')
                    ->numeric(decimalPlaces:0),  
		Tables\Columns\TextColumn::make('updated_at')              
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
            ])
	    ->defaultSort('created_at', 'DESC');
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
            'index' => Pages\ListBankTransfers::route('/'),            
        ];
    }
}
