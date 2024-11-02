<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\InvoiceResource\Pages;
use App\Models\Transactions\Invoices;
use App\Models\Services\ServiceSelesai;
use App\Models\Transactions\InvoicePiutang;
use App\Models\Connect\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class InvoiceResource extends Resource
{
    protected static ?string $model = Invoices::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Transactions';

    protected static ?string $pluralModelLabel = 'Invoice Service';

    public static function form(Form $form): Form
    {
        $selesai = ServiceSelesai::all()->where('service.status', 'Selesai');
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Invoice Service')
                            ->default(fn() => self::GetCode())                           
                            ->readonly()
                            ->required(),   
                        Forms\Components\Select::make('selesai_id')
                            ->label('Kode Service')
                            ->searchable()                                       
                            ->options($selesai->mapWithKeys(function (ServiceSelesai $selesai) {
                                return [$selesai->id => sprintf('%s - %s', $selesai->service->code, $selesai->service->customer->name)];
                            }))
                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                return collect($get('../*.selesai_id'))
                                    ->reject(fn($id) => $id == $state)
                                    ->filter()
                                    ->contains($value);
                            })                                                        
                            ->afterStateUpdated(function($state, callable $set) {
                                $selesai = ServiceSelesai::find($state);                         
                                if ($selesai) {
                                    $set('customer_name', $selesai->service->customer->name);  
                                    if ($selesai->reference === NULL)
                                    {   
                                        $set('reference', '');                                     
                                    } else {                                        
                                        $set('reference', $selesai->sale->code);
                                    }
                                }                                
                            }),
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Customer')
                            ->disabled(), 
                        Forms\Components\TextInput::make('reference')
                            ->label('Faktur Penjualan')
                            ->disabled(),   
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('Generate')                                
                                ->action(function (Forms\Get $get, Forms\Set $set) { 
                                    $selesai = ServiceSelesai::find($get('selesai_id'));                                                                                                                                  
                                    if ($selesai) {                                        
                                        $set('subtotal_service', number_format($selesai->subtotal_service, 0, '', '.'));
                                        $set('totaldiscount_service', number_format($selesai->totaldiscount_service, 0, '', '.'));                                
                                        $set('total', number_format($selesai->total, 0, '', '.'));    
                                        return [
                                            Action::make('delete')
                                                ->requiresConfirmation(),
                                        ];                                    
                                    } 
                                    else {                                          
                                        $set('subtotal_service', 'Generate Kode gagal!!');
                                        $set('totaldiscount_service', 'Generate Kode gagal!!');                                
                                        $set('total', 'Generate Kode gagal!!');              
                                    }
                                }),
                            ])->columnSpan('full'),                           
                    ])->columns([
                        'sm' => 1,
                        'md' => 1,
                        'xl' => 2,
                        '2xl' => 2,
                    ]),                
                Forms\Components\Card::make()
                    ->schema([                                                   
                        Forms\Components\TextInput::make('subtotal_service')
                            ->label('Subtotal Service')
                            ->disabled(),                                
                        Forms\Components\TextInput::make('totaldiscount_service')
                            ->label('Total Discount Service')
                            ->disabled(),    
                        Forms\Components\TextInput::make('total')
                            ->label('Total Invoice')
                            ->disabled(),
                        Forms\Components\TextInput::make('totalbayar')
                            ->label('Total Pembayaran')
                            ->numeric()
                            ->required()     
                            ->default(0)
                            ->live(onBlur:true)                            
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::updateSisaPembayaran($get, $set);
                            }),                                                                                     
                        Forms\Components\TextInput::make('sisa')
                            ->label('Sisa Pembayaran')                            
                            ->disabled()
                            ->dehydrated()
                            ->required(),        
                        Forms\Components\Textarea::make('description')             
                            ->label('Keterangan')                            
                        ])
                        ->columns([
                            'sm' => 1,
                            'md' => 1,
                            'xl' => 3,
                            '2xl' => 3,
                        ])                    
            ]);             
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Faktur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selesai.service.customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('selesai.service.merk')
                    ->label('Merk'),
                Tables\Columns\TextColumn::make('selesai.service.seri')
                    ->label('Seri/Tipe'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('selesai.total')
                    ->label('Total')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('sisa')
                    ->label('Sisa')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'warning',
                        'Cash' => 'success',
                        'Piutang' => 'danger',                        
                    }),                
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Purchase Status')
                    ->options([
                        ''   => 'All',
                        'Lunas' => 'Lunas',                        
                        'Piutang' => 'Piutang',
                        'Cash'  => 'Cash',                         
                    ])                    
                    ->selectablePlaceholder(false),
                Tables\Filters\SelectFilter::make('customers') 
                    ->label('Customers')                                       
                    ->options(Customers::all()->pluck('name', 'id'))                    
                    ->multiple()
                    ->modifyQueryUsing(function (Builder $query, $state) {
                        if (!empty($state['values'])) {
                            $query->whereHas('selesai', fn($query) => 
                            $query->whereHas('service', fn($query) => 
                                $query->whereIn('customer_id', $state['values'])
                            )                            
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
                    Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Details'),
                Tables\Actions\Action::make('pelunasan')->hiddenLabel()->tooltip('Pelunasan')
                    ->label('Pelunasan')
                    ->color('warning')
                    ->icon('heroicon-o-queue-list')                    
                    ->form([                                  
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Penjualan')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn(Invoices $record): string => $record->code),
                        Forms\Components\TextInput::make('sisa')
                            ->label('Sisa Pembayaran')
                            ->disabled()                        
                            ->default(fn(Invoices $record): string => number_format($record->sisa, '0', '', '.')),                        
                        // Forms\Components\Hidden::make('tot_bayar')
                        //     ->default(fn(Jual $record) => $record->tot_bayar),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pelunasan')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('bayar')
                            ->label('Nominal Pembayaran')                            
                            ->required(),
                    ])
                    ->action(function (array $data, Invoices $invoice): void {                        
                        $record[] = array();
                        $record['user_id'] = Auth::id();
                        $record['invoice_id'] = $invoice->id;                        
                        $record['bayar']   = $data['bayar'];                        
                        $sisa = $invoice->sisa - $data['bayar'];
                        $totalbayar = $invoice->totalbayar + $data['bayar'];
                        if ($sisa > 0) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Lunas';
                        }
                        InvoicePiutang::Create($record);
                        Invoices::where('id', $invoice->id)->update([
                            'sisa'      => $sisa,
                            'status'    => $status,
                            'totalbayar' => $totalbayar,
                        ]);                        
                    })
                    ->visible(fn (Invoices $record): bool => $record->status === 'Piutang')
                    ->modalWidth(MaxWidth::Medium),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\Action::make('print')                    
                        ->url(fn ($record) => '/print/invoicereceipt/'.$record->id)
                        ->color('warning')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab(),  
                Tables\Actions\DeleteAction::make(),                
                ])                    
            ])
            ->modifyQueryUsing(function (Builder $query) { 
                if (auth()->user()->role === 'teknisi') {
                    return $query->whereHas('selesai', function ($q) {
                        $q->where('teknisi_id', auth()->id());
                    });                  
                }                                
            })
	      ->defaultSort('updated_at', 'DESC');
    }    

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Service Details')
                    ->schema([
                        TextEntry::make('selesai.service.code')
                            ->label('Kode Service')
                            ->weight(FontWeight::Bold),                        
                        TextEntry::make('selesai.service.customer.name')
                            ->label('Nama Customer'), 
                        TextEntry::make('selesai.teknisi.name')
                            ->label('Nama Teknisi'),
                        TextEntry::make('selesai.service.merk')
                            ->label('Merk/Brand'),
                        TextEntry::make('selesai.service.seri')
                            ->label('Seti/Tipe'),                        
                        TextEntry::make('selesai.service.keluhan')
                            ->label('Keluhan'), 
                        TextEntry::make('selesai.subtotal_service')
                            ->label('Subtotal Service')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                                                                                                 
                        TextEntry::make('selesai.totaldiscount_service')
                            ->label('Total Discount')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),    
                        TextEntry::make('selesai.total')
                            ->label('Total')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                         
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->colors([
                                'success'   => 'Cash',
                                'warning'   => 'Lunas',
                                'danger'    => 'Piutang'
                            ]), 
                        TextEntry::make('totalbayar')
                            ->label('Total Dibayarkan')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),        
                        TextEntry::make('sisa') 
                            ->label('Sisa Pembayaran')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),        
                ])->columns(2),                                                             
                RepeatableEntry::make('detailPiutang')
                    ->label('Riwayat Pelunasan Piutang')
                    ->schema([                                                                                                                                                                                                  
                        TextEntry::make('created_at'),
                        TextEntry::make('bayar')                            
                            ->label('Nomimal')
                            ->money('IDR')
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2),                   
            ]);
    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {
        $total = (int)str_replace('.', '', $get('total'));                                     
        $sisa = $total - $get('totalbayar');        
        $set('sisa', number_format($sisa, 0, '', '.'));        

    }

    public static function getCode(): string
    {
        $date = Carbon::now()->format('my');
        $last = Invoices::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                            
            $tmp = substr($last, 8, 4)+1;
            $code =  "FKS-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            $code = "FKS-".$date."001";
        }

        return $code;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'view' => Pages\ViewInvoice::route('/{record}/view')
        ];
    }
}
