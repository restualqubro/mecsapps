<?php

namespace App\Filament\Clusters\Finance\Resources;

use App\Filament\Clusters\Finance;
use App\Filament\Clusters\Finance\Resources\PengeluaranResource\Pages;
use App\Filament\Clusters\Finance\Resources\PengeluaranResource\RelationManagers;
use App\Models\Finance\Pengeluaran;
use App\Models\Finance\FinanceCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-start-on-rectangle';

    protected static ?string $pluralModelLabel = 'Pengeluaran';

    protected static ?string $slug = 'transaksi-pengeluaran';    


    protected static ?string $cluster = Finance::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->required()
                    ->options(FinanceCategories::where('jenis', 'Pengeluaran')->get()->pluck('name', 'id')),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Pengeluaran')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->columnSpan(2),
                Forms\Components\Hidden::make('submitted_id')
                    ->default(auth()->id())
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Aprrove')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')                        
                        ->requiresConfirmation()
                        ->modalHeading('Approve')
                        ->color('success')
                        ->modalDescription('Are you sure you\'d like to approve this ?')
                        ->modalSubmitActionLabel('Yes, approve it')
                        ->action(fn (Pengeluaran $record) => $record->update([
                            'status'        => 'Approve',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(Pengeluaran $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\Action::make('Reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->modalHeading('Reject')
                        ->modalDescription('Are you sure you\'d like to reject this ?')
                        ->modalSubmitActionLabel('Yes, reject it')
                        ->action(fn (Pengeluaran $record) => $record->update([
                            'status'        => 'Reject',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(Pengeluaran $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\DeleteAction::make(),
                ])
                
            ])
            ->defaultSort('created_at', 'DESC')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) { 
                if (auth()->user()->role !== 'super_admin') { 
                    return $query->where('category_id', '!=', 'DLL'); 
                } 
            }) ;
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([                                
                TextEntry::make('category.name')
                    ->label('Categories'),
                TextEntry::make('nominal')                                                          
                    ->label('Nominal')
                    ->money('IDR'),
                TextEntry::make('submitted.name')
                    ->label('Submitted by'),
                TextEntry::make('approval.name')                    
                    ->label('Approval by'),
                TextEntry::make('created_at')
                    ->label('Submitted at'),
                TextEntry::make('updated_at')
                    ->label('Last Updated'),
                TextEntry::make('description')
                    ->label('Description'),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success'   => 'Approved',
                        'danger'    => 'Reject',
                        'gray'      => 'Baru'
                    ]),
                                    
            ])->columns(2);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengeluarans::route('/'),            
        ];
    }
}
