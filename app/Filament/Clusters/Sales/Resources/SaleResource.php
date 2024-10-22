<?php

namespace App\Filament\Clusters\Sales\Resources;

use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Sales\Resources\SaleResource\Pages;
use App\Filament\Clusters\Sales\Resources\SaleResource\RelationManagers;
use App\Models\Transactions\Sale;
use App\Models\Transactions\SalePreorders;
use App\Models\Transactions\SalePiutang;
use App\Models\Connect\Customers;
use App\Models\Products\ProductStocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Support\Enums\FontWeight;
use Carbon\Carbon;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;    

    protected static ?string $slug = 'sale';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $pluralModelLabel = 'Sale';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;    

    protected static ?string $cluster = Sales::class;

    public static function form(Form $form): Form
    {
        $stock = ProductStocks::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Grid::make()                        
                            ->schema([                            
                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('code')
                                                    ->label('Faktur Penjualan')
                                                    ->default(fn() => self::getCode())
                                                    ->readonly()
                                                    ->required()
                                                    ->columnSpan([
                                                        'md' => 2
                                                    ]),                                
                                                Forms\Components\DatePicker::make('created_at')
                                                    ->default(fn() => Carbon::now())
                                                    ->required()
						    ->disabled()
                                                    ->columnSpan([
                                                        'md' => 2
                                                    ]),                                                               
                                                Forms\Components\Select::make('customer_id')
                                                    ->label('Customer')
                                                    ->required()
                                                    ->options(Customers::all()->pluck('name','id'))
                                                    ->columnSpan([
                                                        'md' => 2
                                                    ]), 
                                                Forms\Components\Toggle::make('is_pending')
                                                    ->label('is pending ?')                                                    
                                                    ->onColor('success')
                                                    ->offColor('gray')   
                                                    ->columnSpan(6)                                   
                                            ])->columns(6),                                
                                        Forms\Components\Group::make()
                                        ->schema([
                                            Forms\Components\Textarea::make('description')
                                                ->rows(1)                                                                    
                                        ])
                                        ->columns('full')
                                    ])->columnSpan(6),
                                Forms\Components\Card::make()                                    
                                    ->schema([                                          
                                        Forms\Components\Select::make('preorder_id')
                                            ->label('Kode Preorder')        
                                            ->reactive()
                                            ->searchable()                                       
                                            ->options(SalePreorders::where('status', 'Baru')->pluck('code','id'))                                                                                     
                                            ->columnSpan([
                                                'md' => 2
                                            ])
                                            ->afterStateUpdated(function($state, Forms\Set $set) {
                                                $preorder = SalePreorders::find($state);
                                                if ($preorder) {                                                    
                                                    $set('nominal_dp', number_format($preorder->nominal, 0, '', '.'));    
                                                    $set('totaldp', number_format($preorder->nominal, 0, '', '.'));                                                                                                    
                                                }
                                                }
                                            ), 
                                        Forms\Components\TextInput::make('nominal_dp')
                                            ->label('Nominal DP')                                            
                                            ->disabled()                                                                                        
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                        
                                    ])->columnSpan(2),
                            ])
                            ->columns(8),                        
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Forms\Components\Repeater::make('saleDetails')
                                    ->label('Detail Items')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('Kode Stock')                                                                                        
                                            ->options(                                                
                                                $stock->mapWithKeys(function (ProductStocks $stock) {
                                                    return [$stock->id => sprintf('%s-%s | %s', $stock->item->code, $stock->code, $stock->item->name)];
                                                })
                                                )                                                                                            
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                                return collect($get('../*.stock_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            }) 
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $stock = ProductStocks::find($state);
                                                if ($stock) {                                                                                                        
                                                    $set('hjual', $stock->item->hjual);
                                                    $set('hbeli', $stock->hbeli);
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                  
                                        Forms\Components\Hidden::make('hbeli'),                                        
                                        Forms\Components\TextInput::make('hjual')                                            
                                            ->label('Harga')
                                            ->disabled() 
                                            ->dehydrated()                                           
                                            ->columnSpan([
                                                'md' => 1
                                            ]),                                        
                                        Forms\Components\TextInput::make('disc')                                            
                                            ->label('Discount')
                                            ->numeric()    
                                            ->required() 
                                            ->default(0)                                       
                                            ->columnSpan([
                                                'md' => 1
                                            ]),
                                        Forms\Components\TextInput::make('qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(function(ProductStocks $stock, Forms\Get $get, $record): int {
                                                if ($record) {                                                    
                                                    $items = $stock->find($record->stock_id);                                                                                                        
                                                    $max = $items->item->sum + $record->qty;            
                                                }  else {
                                                    $items = $stock->where('id', $get('stock_id'))->first();
                                                    $max =  $items->item->sum;            
                                                }  
                                                return $max;
                                            })                                                
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live(onBlur:true)
                                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                                self::getItemState($get, $set);   
                                            }), 
                                        Forms\Components\Hidden::make('profit'),                                       
                                        Forms\Components\TextInput::make('jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalHarga($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalHarga($get, $set)),
                                    )                                                                      
                                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                        $data['jumlah'] = number_format($data['qty'] * ($data['hjual'] - $data['disc']), 0, '', '.');
                                 
                                        return $data;
                                    })                                    
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                        Forms\Components\Card::make()                                                                                              
                            ->schema([                            
                            Forms\Components\TextInput::make('totaldp')
                                ->label('Uang Muka / DP')                                                                    
                                ->disabled()
                                ->dehydrated()
                                ->default(0), 
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                             
                            Forms\Components\TextInput::make('totaldiscount')
                                ->label('Total Discount')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                                                           
                            Forms\Components\TextInput::make('total')
                                ->label('Total')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                                                               
                            Forms\Components\TextInput::make('totalbayar')
                                ->label('Total di Bayarkan')
                                ->numeric()
                                ->minValue(0)
                                ->live(onBlur:true)
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                    self::updateSisaPembayaran($get, $set);
                                })
                                ->required(),                            
                            Forms\Components\TextInput::make('sisa')
                                ->label('Sisa Pembayaran')                                
                                ->disabled()
                                ->dehydrated(),
                            Forms\Components\Hidden::make('status')                                
                            ])->columns(3),
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur Penjualan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal')
                    ->date(),                
                Tables\Columns\TextColumn::make('customer.name')        
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')                    
                    ->label('Total Harga')
                    ->money('IDR'),
                Tables\Columns\IconColumn::make('is_pending')                
                    ->icon(fn (string $state): string => match ($state) {
                        '1'   => 'heroicon-o-check-circle',
                        '0'   => 'heroicon-o-x-circle'
                    })
                    ->boolean(),                                        
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {                        
                        'Lunas' => 'warning',
                        'Cash' => 'success',
                        'Piutang' => 'danger',
                    })
                    ->label('Status Pembayaran')            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),  
                    Tables\Actions\Action::make('print')
                        ->hiddenLabel()
                        ->tooltip('Print')
                        ->url(fn ($record) => '/print/fakturjual/'.$record->id)
                        ->color('primary')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab(), 
                    Tables\Actions\Action::make('pelunasan')
                        ->label('Pelunasan')
                        ->color('warning')
                        ->icon('heroicon-o-queue-list')                    
                        ->form([  
                            Forms\Components\Hidden::make('sale_id')                                                        
                                ->default(fn(Sale $record): string => $record->id),                      
                            Forms\Components\TextInput::make('code')
                                ->label('Faktur Penjualan')
                                ->disabled()
                                ->dehydrated()
                                ->default(fn(Sale $record): string => $record->code),
                            Forms\Components\TextInput::make('out_sisa')
                                ->label('Sisa Pembayaran')
                                ->disabled()                        
                                ->default(fn(Sale $record): string => number_format($record->sisa, '0', '', '.')),
                            Forms\Components\Hidden::make('sisa')
                                ->default(fn(Sale $record) => $record->sisa),
                            Forms\Components\Hidden::make('totalbayar')
                                ->default(fn(Sale $record) => $record->totalbayar),                            
                            Forms\Components\TextInput::make('bayar')
                                ->label('Nominal Pembayaran')                            
                                ->required(),
                        ])
                        ->action(function (array $data): void {                        
                            $record[] = array();
                            $record['user_id'] = auth()->id();
                            $record['sale_id'] = $data['sale_id'];                            
                            $record['bayar']   = $data['bayar'];                        
                            $sisa = $data['sisa'] - $data['bayar'];
                            $bayar = $data['totalbayar'] + $data['bayar'];
                            if ($sisa > 0) {
                                $status = 'Piutang';
                            } else {
                                $status = 'Lunas';
                            }
                            SalePiutang::Create($record);
                            Sale::where('id', $data['sale_id'])->update([
                                'sisa'      => $sisa,
                                'status'    => $status,
                                'totalbayar' => $bayar,
                            ]);
                        })->visible(fn (Sale $record): bool => $record->status === 'Piutang')
                            ->modalWidth(MaxWidth::Medium),             
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record): bool => $record->status === 'Lunas' || $record->status === 'Cash'),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn($record): bool => $record->status === 'Lunas' || $record->status === 'Cash'),
                ])                                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
	    ->defaultSort('updated_at', 'DESC');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Sale Details')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Nomor Faktur')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('created_at')
                            ->label('Created at'), 
                        TextEntry::make('customer.name')
                            ->label('Nama Customer'),                                                 
                        TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'success'   => 'Cash',
                                'warning'   => 'Lunas',
                                'danger'    => 'Piutang'
                            ]),
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                                                                                                 
                        TextEntry::make('totalbayar')
                            ->label('Total Pembayaran')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),    
                        TextEntry::make('sisa')
                            ->label('Sisa Pembayaran')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('preorder.code')
                            ->label('Preorder')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('preorder.nominal')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('description')
                ])->columns(2),                                                             
                RepeatableEntry::make('saleDetails')
                    ->label('Detail Items')
                    ->schema([                                                                                                                                                                          
                        TextEntry::make('productStocks.fullcode')                        
                            ->label('Code')
                            ->columnSpan(3),
                        TextEntry::make('productStocks.item.name')                        
                            ->label('Items')
                            ->columnSpan(3),
                        TextEntry::make('productStocks.item.sale_warranty')                          
                            ->columnSpan(3),
                        TextEntry::make('qty')
                            ->label('Qty')
                            ->columnSpan(3),  
                        TextEntry::make('hjual')
                            ->money('IDR')
                            ->label('Harga Satuan')
                            ->columnSpan(2),
                        TextEntry::make('disc')
                            ->money('IDR')
                            ->label('Discount')
                            ->columnSpan(2),                                          
                        TextEntry::make('jumlah')                            
                            ->money('IDR')
                            ->columnSpan(2)
                    ])      
                    ->columns(6) 
                    ->columnSpan('full')
                    ->grid(2)
            ]);
    }

    public static function updateTotalHarga(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('saleDetails'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hjual']));        
        $tot_dp = 0;
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal += $item['hjual'] * $item['qty'];
            $totaldiscount += $item['disc'] * $item['qty'];            
        }                      
        $totaldp = (int)str_replace('.', '', $get('totaldp'));        
        $total = $subtotal - $totaldiscount - $totaldp;
        // Update the state with the new values
        $set('subtotal', number_format($subtotal, 0, '', '.'));
        $set('totaldiscount', number_format($totaldiscount, 0, '', '.'));        
        $set('total', number_format($total, 0, '', '.'));        
        

    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {                       
        $totalharga = (int)str_replace('.', '', $get('total'));   
        if (!empty($totalharga)) {            
            $totaldp = (int)str_replace('.', '', $get('totaldp'));
            $totalbayar = (int)str_replace('.', '', $get('totalbayar'));
            if (!empty($totalbayar))
            {
                $sisa = $totalharga - $totaldp - $totalbayar;
                if ($sisa > 0) {                
                    $status = 'Piutang';        
                    $set('status', $status);
                } else {                
                    $status = 'Cash';
                    $set('status', $status);
                }
            } else {
                $sisa = $totalharga;
                $set('status', 'Piutang');
            }                        
        } else {
            [
                Notification::make()
                    ->title('Purchase Denied')
                    ->body('Total harga kosong, input item terlebih dahulu agar totalharga terisi')
                    ->warning()
                    ->send()
            ];
        }        
        $set('sisa', number_format($sisa, 0, '', '.'));                  
    }

    public static function getCode(): string
    {        
        $date = Carbon::now()->format('my');
        $last = Sale::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                            
            $tmp = substr($last, 8, 4)+1;
            return "FKJ-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            return "FKJ-".$date."001";
        }
    } 
    
    public static function getItemState(Forms\Get $get, Forms\Set $set): void {
        $disc = $get('disc');
        $qty = $get('qty');
        $hjual = $get('hjual');
        $hbeli = $get('hbeli');
        $jumlah = $qty * ($hjual - $disc);
        $profit = (($hjual - $disc) - $hbeli) * $qty;
        // return dd($jumlah);
        $set('profit', $profit);
        $set('jumlah', number_format($jumlah, 0, '', '.'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
            'view' => Pages\ViewSale::route('/{record}/view'),
        ];
    }
}
