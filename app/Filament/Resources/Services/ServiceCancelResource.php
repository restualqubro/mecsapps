<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceCancelResource\Pages;
use App\Filament\Resources\Services\ServiceCancelResource\RelationManagers;
use App\Models\Services\ServiceCancel;
use App\Models\Services\ServiceData;
use App\Models\Services\ServiceLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceCancelResource extends Resource
{
    protected static ?string $model = ServiceCancel::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 2;

    protected static ?string $pluralModelLabel = 'Service Cancel';

    protected static ?string $slug = 'service-cancel';    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.code')
                    ->label('Kode Service')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.merk')
                    ->label('Merk / Brand'),
                Tables\Columns\TextColumn::make('service.seri')
                    ->label('Seri / Tipe'),
                Tables\Columns\TextColumn::make('service.customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('teknisi.name')
                    ->label('Teknisi'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->badge()                    
                    ->default(function ($record) {
                        return $record->isKeluar < 1 || $record->isKeluar === null ? "Belum diambil" : "Diambil";
                    })
                    ->color(fn ($record): string => $record->isKeluar < 1 || $record->isKeluar === null ? "secondary" : "success")
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('isKeluar')
                    ->hiddenLabel()
                    ->tooltip('Pengambilan Unit')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Pengambilan Unit')
                    ->modalDescription('Anda yakin unit tersebut sudah diambil oleh customer ?')
                    ->action(function ($record): void {                                                                                                
                        ServiceCancel::where('id', $record['id'])->update([                              
                                'isKeluar'  => TRUE                   
                        ]);
                        ServiceData::where('id', $record->service_id)->update([
                            'status'    => 'Keluar'
                        ]);
                        ServiceLog::create([
                            'service_id'    => $record->service_id,
                            'status'        => 'Keluar',
                            'description'   => 'Unit sudah diambil oleh Customer',
                            'user_id'       => auth()->user()->id
                        ]);
                    })->hidden(fn($record): bool => ($record->isKeluar === 1 && auth()->user()->roles->pluck('name')[0] === 'customer_service')),
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
            'index' => Pages\ListServiceCancels::route('/'),            
        ];
    }
}
