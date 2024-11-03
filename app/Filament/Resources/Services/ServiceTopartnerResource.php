<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceTopartnerResource\Pages;
use App\Filament\Resources\Services\ServiceTopartnerResource\RelationManagers;
use App\Models\Services\ServiceTopartner;
use App\Models\Services\ServiceData;
use App\Models\Connect\Partners;
use App\Models\Services\ServiceLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class ServiceTopartnerResource extends Resource
{
    protected static ?string $model = ServiceTopartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 3;

    protected static ?string $pluralModelLabel = 'Service to Partner';

    protected static ?string $slug = 'service-topartner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Kode Service')
                    ->searchable()
                    ->reactive()
                    ->options(ServiceData::all()->where('status', 'Proses')->pluck('code', 'id')),
                Forms\Components\Select::make('partner_id')
                    ->label('Partner')
                    ->searchable()
                    ->reactive()
                    ->options(Partners::all()->pluck('name', 'id')),                
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.code')
                    ->label('Kode Service'),
                Tables\Columns\TextColumn::make('service.customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.merk') 
                    ->label('Merk / Brand'),
                Tables\Columns\TextColumn::make('service.seri')
                    ->label('Seri / Tipe'),
                Tables\Columns\TextColumn::make('partner.name'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Kirim' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Kembali' => 'gray',
                    })
            ])
            ->filters([                 
                Tables\Filters\SelectFilter::make('partner_id') 
                    ->label('by Partners')                                       
                    ->options(Partners::all()->pluck('name', 'id'))                    
                    ->multiple(),                                            
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
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record): string => ($record->status != 'Kirim' || $record->status != 'Proses')),
                    Tables\Actions\Action::make('status_edit')
                        ->label('Update')
                        ->color('secondary')
                        ->icon('heroicon-o-document-check')
                        ->form([
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Update Details')
                                                            
                        ])
                        ->action(function (array $data, ServiceTopartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description'];                                  
                            ServiceLog::create($record);
                            ServiceTopartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'status'   => 'Proses'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->hidden(fn($record): string => ($record->status != 'Kirim' && $record->status != 'Proses')),
                    Tables\Actions\Action::make('selesai') 
                        ->label('Selesai')                     
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->form([
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Update Details'),
                            Forms\Components\TextInput::make('biaya')                                                        
                                ->label('Biaya')
                                ->numeric()
                        ])
                        ->action(function (array $data, ServiceTopartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description']; 
                            $record['status'] = 'Selesai';
                            ServiceLog::create($record);
                            ServiceTopartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'biaya'     => $data['biaya'],
                                'status'   => 'Selesai'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->hidden(fn($record): string => ($record->status != 'Proses')),
                    Tables\Actions\Action::make('cancel') 
                        ->label('Cancel')                       
                        ->color('danger')
                        ->icon('heroicon-o-no-symbol')
                        ->form([
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Update Details'),                        
                        ])
                        ->action(function (array $data, ServiceTopartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description']; 
                            $record['status'] = 'Cancel';
                            ServiceLog::create($record);
                            ServiceTopartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'status'   => 'Cancel'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->hidden(fn($record): string => ($record->status != 'Proses')),
                    Tables\Actions\Action::make('ambil')
                        ->label('Pengambilan')
                        ->color('info')
                        ->icon('heroicon-o-truck')
                        ->form([
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Update Details'),                        
                            Forms\Components\TextInput::make('biaya')
                                ->label('Biaya') 
                                ->default(fn($record): string => ($record->biaya))                           
                                ->disabled(),
                            Forms\Components\TextInput::make('bayar')
                                ->label('Bayar')
                                ->afterStateUpdated(function(Forms\Get $get, Forms\Set $set) {
                                    $biaya = $get('biaya');
                                    $bayar = $get('bayar');
                                    $sisa = $biaya - $bayar;
                                    $set('sisa', $sisa);
                                    if ($sisa > 0) {
                                        $set('status_pembayaran', 'Belum Lunas');
                                    } else {
                                        $set('status_pembayaran', 'Lunas');
                                    }
                                })
                                ->live(),
                            Forms\Components\TextInput::make('sisa')
                                ->label('Sisa Pembayaran'),
                            Forms\Components\Hidden::make('status_pembayaran')
                        ])
                        ->action(function (array $data, ServiceTopartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description']; 
                            $record['status'] = 'Cancel';
                            ServiceLog::create($record);
                            ServiceTopartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'status_pembayaran' => $data['status_pembayaran'],
                                'status'   => 'Kembali'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->hidden(fn($record): string => ($record->status != 'Selesai')),
                    Tables\Actions\DeleteAction::make(),    
                ])                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([                
                TextEntry::make('service.code')
                    ->label('Kode Service')
                    ->weight(FontWeight::Bold),
                TextEntry::make('service.customer.name')
                    ->label('Nama Customer'),
                TextEntry::make('service.merk')
                    ->label('Merk/Brand'),
                TextEntry::make('service.seri')
                    ->label('Seti/Tipe'),      
                TextEntry::make('date_send')
                    ->label('Tanggal Dikirim'),                
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Kembali' => 'gray',
                    }),
                TextEntry::make('biaya')
                    ->label('Biaya')
                    ->money('IDR'),
                TextEntry::make('status_pembayaran')
                    ->label('Status Pembayaran')                    
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {                    
                        'Lunas' => 'success',
                        'Belum Lunas' => 'warning',                        
                    }),                          
            ])->columns(2);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceTopartners::route('/'),            
        ];
    }
}
