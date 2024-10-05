<?php

namespace App\Filament\Clusters\Peminjaman\Resources;

use App\Filament\Clusters\Peminjaman;
use App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource\Pages;
use App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource\RelationManagers;
use App\Models\Products\PeminjamanPart;
use App\Models\Products\ProductStocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PeminjamanPartResource extends Resource
{
    protected static ?string $model = PeminjamanPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Peminjaman::class;

    public static function form(Form $form): Form
    {
        $stock = ProductStocks::get();
        return $form
            ->schema([
                Forms\Components\Select::make('stock_id')
                    ->label('Kode Stock')                                                                                        
                    ->options(                                                
                        $stock->mapWithKeys(function (ProductStocks $stock) {
                            return [$stock->id => sprintf('%s-%s | %s', $stock->item->code, $stock->code, $stock->item->name)];
                        })
                        )                                                                                            
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('qty'),
                Forms\Components\Textarea::make('description')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'xl' => 2,
                        '2xl' => 2,
                    ])            
            ])
            ->columns([
                'sm' => 1,
                'md' => 1,
                'xl' => 2,
                '2xl' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('stock.fullcode'),
                Tables\Columns\TextColumn::make('stock.item.name'),
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
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => $record->status != 'Baru'),
                    Tables\Actions\Action::make('Aprrove')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')                        
                        ->requiresConfirmation()
                        ->modalHeading('Approve')
                        ->color('success')
                        ->modalDescription('Are you sure you\'d like to approve this ?')
                        ->modalSubmitActionLabel('Yes, approve it')
                        ->action(fn ($record) => $record->update([
                            'status'        => 'Approve',
                            'approval_id'   => Auth::id()
                        ]))
                        ->hidden(fn($record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\Action::make('Reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->modalHeading('Reject')
                        ->modalDescription('Are you sure you\'d like to reject this ?')
                        ->modalSubmitActionLabel('Yes, reject it')
                        ->action(fn ($record) => $record->update([
                            'status'        => 'Reject',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn($record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn($record) => $record->status != 'Baru'),
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
            'index' => Pages\ListPeminjamanParts::route('/'),            
        ];
    }
}
