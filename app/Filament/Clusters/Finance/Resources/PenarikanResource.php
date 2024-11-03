<?php

namespace App\Filament\Clusters\Finance\Resources;

use App\Filament\Clusters\Finance;
use App\Filament\Clusters\Finance\Resources\PenarikanResource\Pages;
use App\Filament\Clusters\Finance\Resources\PenarikanResource\RelationManagers;
use App\Models\Finance\Penarikan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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
                Forms\Components\Textarea::make('description'),
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
                    ->limit(50)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sumber')
                    ->label('by Source')
                    ->options([
                        'Cash'      => 'Cash', 
                        'Rekening'  => 'Rekening'
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
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            ) 
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
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
            ])
	    ->defaultSort('created_at', 'DESC');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Sale Details')
                    ->schema([
                        TextEntry::make('sumber')
                            ->label('Sumber')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('created_at')
                            ->label('Created at'), 
                        TextEntry::make('nominal')
                            ->label('Nominal'),                                                 
                        TextEntry::make('description')                                                    
                ])->columns(2),                                                                             
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenarikans::route('/'),           
        ];
    }
}
