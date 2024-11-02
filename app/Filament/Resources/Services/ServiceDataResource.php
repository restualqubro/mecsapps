<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceDataResource\Pages;
use App\Filament\Resources\Services\ServiceDataResource\RelationManagers;
use App\Models\Services\ServiceData;
use App\Models\Services\ServiceLog;
use App\Models\Services\ServiceCancel;
use App\Models\Connect\Customers;
use App\Models\Services\ServiceCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class ServiceDataResource extends Resource
{
    protected static ?string $model = ServiceData::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'service-data';

    protected static ?string $pluralModelLabel = 'Service Data';
    

    public static function form(Form $form): Form
    {        
        return $form
            ->schema([
                Forms\Components\DatePicker::make('created_at')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->default('now')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->required()
                    ->options(Customers::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->required()
                    ->options(ServiceCategories::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\TextInput::make('merk')
                    ->label('Merk')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\TextInput::make('seri')
                    ->label('Seri / Tipe')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
		Forms\Components\TextInput::make('reference')
		    ->label('Nomor Referensi')
		    ->columnSpan([
			'sm' => 2,
			'xl' => 3,
			'2xl' => 4,
		    ]),     		               
                Forms\Components\TextInput::make('sn')          
                    ->label('Serial Number')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\TextInput::make('kelengkapan')
                    ->label('Kelengkapan')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Textarea::make('keluhan')
                    ->label('Keluhan')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),  
                Forms\Components\Hidden::make('status')
                    ->default('Baru')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Hidden::make('code')
                    ->default(function () {
                        $last = ServiceData::whereMonth('created_at', '=', date('m'))
                        ->whereYear('created_at', '=', date('Y'))
                        ->max('code');                          
                        // $code = '';
                        if ($last != null) {                                                                                            
                            $tmp = substr($last, 6, 3)+1;
                            return "SV".Carbon::now()->format('my').sprintf("%03s", $tmp);                                                                            
                        } else {
                            return "SV".Carbon::now()->format('my')."001";
                        }           
                        // $code; 
                    })
            ])->columns([
                'sm' => 3,
                'xl' => 6,
                '2xl' => 8,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Masuk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('merk')
                    ->label('Brand/Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seri')
                    ->label('Seri/Tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Keluar' => 'gray',
                    })
                    ->sortable(),                
            ])            
            ->defaultSort('code', 'DESC')
            ->filters([                
                Tables\Filters\SelectFilter::make('status')
                    ->label('by Status')
                    ->options([
                        'Baru'      => 'Baru',
                        'Proses'    => 'Proses',
                        'Selesai'   => 'Selesai',
                        'Cancel'    => 'Cancel',
                        'Keluar'    => 'Keluar'
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('by Category')
                    ->options(ServiceCategories::all()->pluck('name', 'id'))
                    ->multiple(),
                Tables\Filters\SelectFilter::make('customer_id') 
                    ->label('by Customers')                                       
                    ->options(Customers::all()->pluck('name', 'id'))                    
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
                    Tables\Actions\Action::make('contact')
                        ->label('Contact')
                        ->url(function($record) {
                            
                            // return dd($record);
                            return 'https://wa.me/+62'.$record->customer->telp."?text=Assalamu'alaikum,%20Salam%20Kami%20dari%20Mecs%20Komputer%20Ingin%20Mengupdate%20Unit%20dengan%20kode%20".$record->code."%20atas%20nama%20".$record->customer->name;
                        })
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->openUrlInNewTab()
                        ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'teknisi'),
                    Tables\Actions\Action::make('print')
                        ->label('Print')
                        ->url(fn ($record) => 'print/servicereceipt/'.$record->id)
                        ->color('warning')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab()
                        ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'teknisi'),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),                    
                    Tables\Actions\Action::make('status_edit')
                        ->label('Update')
                        ->color('success')
                        ->icon('heroicon-o-document-check')
                        ->form([
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Update Details')
                                                            
                        ])
                        ->action(function (array $data, ServiceData $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description'];
                            $record['status'] = 'Proses';                        
                            ServiceLog::create($record);
                            ServiceData::where('id', $row->id)->update(['status' => 'Proses']);                                                
                        })->hidden(fn(ServiceData $record) => $record->status !== 'Baru' && $record->status !== 'Proses' || auth()->user()->roles->pluck('name')[0] === 'customer_service'),
                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel')
                        ->color('danger')
                        ->icon('heroicon-o-no-symbol')
                        ->form([
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Cancel Details'),                                                                                        
                        ])
                        ->action(function (array $data, ServiceData $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->id;
                            $record['user_id'] = auth()->user()->id;
                            $record['teknisi_id'] = auth()->user()->id;
                            $record['alasan'] = $data['description'];
                            $record['description'] = $data['description'];
                            $record['status'] = 'Cancel';                        
                            ServiceLog::create($record);
                            ServiceCancel::create($record);
                            ServiceData::where('id', $row->id)->update(['status' => 'Cancel']);
                        })
                        ->hidden(fn(ServiceData $record) => $record->status != 'Proses' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                    Tables\Actions\Action::make('penawaran')
                        ->label('Penawaran')
                        ->color('info')
                        ->icon('heroicon-o-briefcase')
                        ->form([
                            Forms\Components\Select::make('penawaran')
                                ->label('Penawaran')
                                ->options([
                                    'Setuju'        => 'Setuju',
                                    'Tidak Setuju'  => 'Tidak Setuju'
                                ]),
                            Forms\Components\Textarea::make('penawaran_details')                                                                     
                                ->label('Penawaran Details'),                                                                                        
                        ])
                        ->action(function (array $data, ServiceData $row): void {
                            $record[] = array();                                                        
                            $record['penawaran_details'] = $data['penawaran_details'];                            
                            $record['penawaran'] = $data['penawaran'];                            
                            ServiceData::where('id', $row->id)->update([
                                'penawaran'         => $record['penawaran'],
                                'penawaran_details' => $record['penawaran_details']
                            ]);
                        })
                        ->hidden(fn(ServiceData $record) => $record->status != 'Proses' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                    Tables\Actions\DeleteAction::make(),
            ])
            ])
            ->bulkActions([
                
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Service Details')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Service')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('customer.name')
                            ->label('Nama Customer'),
                        TextEntry::make('created_at')
                            ->label('Tanggal Masuk'),
                        TextEntry::make('category.name')
                            ->label('Kategori'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Baru' => 'gray',
                                'Proses' => 'warning',
                                'Selesai' => 'success',
                                'Cancel' => 'danger',
                                'Keluar' => 'gray',
                                
                            }),
                        TextEntry::make('penawaran')
                            ->label('Penawaran')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Setuju' => 'success',
                                'Tidak ' => 'warning',                        
                            }),
                        TextEntry::make('penawaran_details'),
                        TextEntry::make('merk')
                            ->label('Merk/Brand'),
                        TextEntry::make('seri')
                            ->label('Seti/Tipe'),
                        TextEntry::make('reference')
                            ->label('Nomor Referensi / SN Reseller'), 
                        TextEntry::make('sn')
                            ->label('Serial Number/Model'),                                
                        TextEntry::make('kelengkapan')
                            ->label('Kelengkapan'),
                        TextEntry::make('keluhan')
                            ->label('Keluhan'),
                        TextEntry::make('description')
                            ->label('Keterangan/Update'),
                ])->columns(2),                                                             
                RepeatableEntry::make('serviceLog')
                    ->label('History')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Baru' => 'gray',
                                'Proses' => 'warning',
                                'Selesai' => 'success',
                                'Cancel' => 'danger',
                                'Keluar' => 'gray',
                                'Kembali' => 'warning'
                            }),     
                        TextEntry::make('created_at')
                            ->label('DateTime')
                            ->dateTime(),                                                                                                   
                        TextEntry::make('description')
                            ->label('Details'),
                        TextEntry::make('user.name')
                            ->label('Petugas')
                    ])->columns(2)
                    ->columnSpan(2)                                                                        
                                    
            ])->columns(2);
    }    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceData::route('/'),
            'view' => Pages\ViewServiceData::route('/{record}/view')
        ];
    }
}
