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
use Filament\Tables\Actions\Action as FilterAction;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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
                Tables\Filters\SelectFilter::make('type')
                    ->label('by Type')
                    ->options([
                        'Masuk'      => 'Masuk', 
                        'Keluar'    => 'Keluar'
                    ]),                              
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),                   
            ])
            ->filtersTriggerAction(
                fn (FilterAction $action) => $action
                    ->button()
                    ->label('Filter'),
            ) 
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
