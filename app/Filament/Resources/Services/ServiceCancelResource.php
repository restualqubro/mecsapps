<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceCancelResource\Pages;
use App\Filament\Resources\Services\ServiceCancelResource\RelationManagers;
use App\Models\Connect\Customers;
use App\Models\Services\ServiceCancel;
use App\Models\Services\ServiceData;
use App\Models\Services\ServiceLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
                Tables\Columns\TextColumn::make('service.customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('service.merk')
                    ->label('Merk / Brand'),
                Tables\Columns\TextColumn::make('service.seri')
                    ->label('Seri / Tipe'),                
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
                Tables\Filters\SelectFilter::make('customers') 
                    ->label('Customers')                                       
                    ->options(Customers::all()->pluck('name', 'id'))                    
                    ->multiple()
                    ->modifyQueryUsing(function (Builder $query, $state) {
                        if (!empty($state['values'])) {                             
                            $query->whereHas('service', fn($query) => 
                                $query->whereIn('customer_id', $state['values'])
                            );
                        }
                        return $query;
                    }),                                            
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
