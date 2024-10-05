<?php

namespace App\Filament\Clusters\Peminjaman\Resources;

use App\Filament\Clusters\Peminjaman;
use App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource\Pages;
use App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource\RelationManagers;
use App\Models\Products\PengembalianPart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengembalianPartResource extends Resource
{
    protected static ?string $model = PengembalianPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Peminjaman::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peminjaman_id')
                    ->label('Kode Peminjaman')                                                                                                                                                                                                      
                    ->options(PeminjamanPart::where('status', '!=', 'Kembali')->get()->pluck('code', 'name'))
                    ->required()
                    ->searchable(),                
                Forms\Components\Textarea::make('description')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([                
            Tables\Columns\TextColumn::make('peminjaman.stock_id'),
            Tables\Columns\TextColumn::make('peminjaman.stock.product.name'),
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
                        ->action(fn (PeminjamanPart $record) => $record->update([
                            'status'        => 'Approve',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(PeminjamanPart $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\Action::make('Reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->modalHeading('Reject')
                        ->modalDescription('Are you sure you\'d like to reject this ?')
                        ->modalSubmitActionLabel('Yes, reject it')
                        ->action(fn (PeminjamanPart $record) => $record->update([
                            'status'        => 'Reject',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(PeminjamanPart $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPengembalianParts::route('/'),
            'create' => Pages\CreatePengembalianPart::route('/create'),
            'edit' => Pages\EditPengembalianPart::route('/{record}/edit'),
        ];
    }
}
