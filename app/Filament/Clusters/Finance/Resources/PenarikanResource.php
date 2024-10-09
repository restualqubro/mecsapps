<?php

namespace App\Filament\Clusters\Finance\Resources;

use App\Filament\Clusters\Finance;
use App\Filament\Clusters\Finance\Resources\PenarikanResource\Pages;
use App\Filament\Clusters\Finance\Resources\PenarikanResource\RelationManagers;
use App\Models\Finance\Penarikan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenarikanResource extends Resource
{
    protected static ?string $model = Penarikan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $pluralModelLabel = 'Penarikan Tunai';
    
    protected static ?string $slug = 'penarikan-tunai';

    protected static ?string $cluster = Finance::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sumber')
                    ->label('Sumber Saldo')
                    ->required()
                    ->options([
                        'Cash'      => 'Cash',
                        'Rekening'  => 'Rekening'
                    ]),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Pengambilan')
                    ->required(),
                Forms\Components\Hidden::make('submitted_id')                    
                    ->default(fn() => auth()->id()),
                Forms\Components\Hidden::make('status')                    
                    ->default('Baru')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sumber'),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->numeric(decimalPlaces:0),
                Tables\Columns\TextColumn::make('status')
                    ->label('Submitted By')   
                    ->badge()
                    ->colors([
                        'success'   => 'Approved',
                        'danger'    => 'Reject',
                        'gray'      => 'Baru'
                    ]),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Penarikan $record) => $record->update([
                            'status'        => 'approve',
                            'approval_id'   => auth()->id()
                        ])),                                                
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
            'index' => Pages\ListPenarikans::route('/'),           
        ];
    }
}
