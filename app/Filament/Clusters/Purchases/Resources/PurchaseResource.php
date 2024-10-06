<?php

namespace App\Filament\Clusters\Purchases\Resources;

use App\Filament\Clusters\Purchases;
use App\Filament\Clusters\Purchases\Resources\PurchaseResource\Pages;
use App\Models\Transactions\Purchase;
use App\Models\Transactions\PurchaseUtang;
use App\Models\Products\ProductStocks;
use App\Models\Connect\Suppliers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Repeater;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;    

    protected static ?string $pluralModelLabel = 'Purchase';
    
    public static function form(Form $form): Form
    {
        $stock = ProductStocks::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('code')
                                            ->label('Faktur Pembelian')
                                            ->default(fn() => self::getCode())
                                            ->readonly()
                                            ->required()
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                
                                        Forms\Components\DatePicker::make('created_at')
                                            ->default(now())                                                           
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                               
                                        Forms\Components\Select::make('supplier_id')
                                            ->label('Supplier')
                                            ->required()
                                            ->searchable()
                                            ->options(Suppliers::all()->pluck('name','id'))
                                            ->columnSpan([
                                                'md' => 2
                                            ]), 
                                    ])->columns(6),                                
                                Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Textarea::make('description'),                                                                    
                                ])                                
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Repeater::make('purchaseDetails')
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
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                                 
                                        Forms\Components\TextInput::make('supplier_warranty')                                            
                                            ->label('Garansi')
                                            ->numeric()    
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 1
                                            ]),
                                        Forms\Components\TextInput::make('hbeli')                                            
                                            ->label('Harga')
                                            ->numeric()    
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 1
                                            ]),                                        
                                        Forms\Components\TextInput::make('qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()                                                                                                                                                                                                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $qty = $get('qty');
                                                    $hbeli = $get('hbeli');
                                                    $jumlah = $qty * $hbeli;
                                                    $set('jumlah', number_format($jumlah, 0, '', '.'));
                                                }
                                            ),                                        
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
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotals($get, $set)),
                                    )
                                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                        $data['jumlah'] = number_format($data['qty'] * $data['hbeli'], 0, '', '.');
                                 
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
                            Forms\Components\TextInput::make('totalharga')
                                ->label('Subtotal')                                    
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
                    ->label('Faktur Pembelian')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date(),                
                Tables\Columns\TextColumn::make('supplier.name')        
                    ->label('Supplier'),
                Tables\Columns\TextColumn::make('totalharga')                    
                    ->money('IDR')
                    ->label('Total Harga'),
                Tables\Columns\BadgeColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {                        
                        'Lunas' => 'warning',
                        'Cash' => 'success',
                        'Utang' => 'danger',
                    })
                    ->label('Status Pembayaran')            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),
                Tables\Actions\Action::make('pelunasan')->hiddenLabel()->tooltip('Pelunasan')
                    ->label('Pelunasan')
                    ->color('warning')
                    ->icon('heroicon-o-queue-list')                    
                    ->form([  
                        Forms\Components\Hidden::make('beli_id')                                                        
                            ->default(fn(Purchase $record): string => $record->id),                      
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Pembelian')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn(Purchase $record): string => $record->code),
                        Forms\Components\TextInput::make('out_sisa')
                            ->label('Sisa Pembayaran')
                            ->disabled()                        
                            ->default(fn(Purchase $record): string => number_format($record->sisa, '0', '', '.')),
                        Forms\Components\Hidden::make('sisa')
                            ->default(fn(Purchase $record) => $record->sisa),
                        Forms\Components\Hidden::make('tot_bayar')
                            ->default(fn(Purchase $record) => $record->tot_bayar),                       
                        Forms\Components\TextInput::make('bayar')
                            ->label('Nominal Pembayaran')                            
                            ->required(),
                    ])
                    ->action(function (array $data): void {                        
                        $record[] = array();
                        $record['user_id'] = auth()->user()->id;
                        $record['beli_id'] = $data['beli_id'];
                        $record['tanggal'] = $data['tanggal'];
                        $record['bayar']   = $data['bayar'];                        
                        $sisa = $data['sisa'] - $data['bayar'];
                        $bayar = $data['tot_bayar'] + $data['bayar'];
                        if ($sisa > 0) {
                            $status = 'Utang';
                        } else {
                            $status = 'Lunas';
                        }
                        PurchaseUtang::Create($record);
                        Purchase::where('id', $data['beli_id'])->update([
                            'sisa'      => $sisa,
                            'status'    => $status,
                            'tot_bayar' => $bayar,
                        ]);
                    })->visible(fn (Purchase $record): bool => $record->status === 'Utang')
                    ->modalWidth(MaxWidth::Medium),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                ]),                                
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
                Section::make('Pembelian Details')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Nomor Faktur')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('created_at')
                            ->label('Created at'), 
                        TextEntry::make('supplier.name')
                            ->label('Nama Supplier'),                                                 
                        TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'success'   => 'Cash',
                                'warning'   => 'Lunas',
                                'danger'    => 'Utang'
                            ]),
                        TextEntry::make('totalahrga')
                            ->label('Total Harga Pembelian')
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
                        TextEntry::make('description')
                ])->columns(2),                                                             
                RepeatableEntry::make('purchaseDetails')
                    ->label('Detail Items')
                    ->schema([                                                                                                                                                                          
                        TextEntry::make('stock.fullcode')                        
                            ->label('Code')
                            ->columnSpan(4),
                        TextEntry::make('stock.product.name')                        
                            ->label('Items')
                            ->columnSpan(4),
                        TextEntry::make('supplier_warranty')                          
                            ->columnSpan(2),
                        TextEntry::make('hbeli')
                            ->money('IDR')
                            ->label('Harga Satuan')
                            ->columnSpan(2),
                        TextEntry::make('qty')
                            ->label('Qty')
                            ->columnSpan(2),                    
                        TextEntry::make('jumlah')                            
                            ->money('IDR')
                    ])      
                    ->columns(8) 
                    ->columnSpan('full')
                    ->grid(2)
            ]);
    }

    public static function getCode(): string
    {
        $date = Carbon::now()->format('my');
        $last = Purchase::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                            
            $tmp = substr($last, 8, 4)+1;
            return "FKB-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            return "FKB-".$date."001";
        }
    }

    public static function updateTotalHarga(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('purchaseDetails'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hbeli']) && !empty($item['jumlah']));        
        $total = 0;
        $totalqty = 0;
        foreach($selectedProducts as $item) {
            $subtotal = $item['hbeli'] * $item['qty'];
            $totalqty += $item['qty'];
            $total+= $subtotal;
        }              

        // Update the state with the new values
        $set('totalharga', number_format($total, 0, '', '.'));        
        

    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set)
    {     
        $totalharga = (int)str_replace('.', '', $get('totalharga'));        
        if (!empty($totalharga)) {            
            $totalbayar = $get('totalbayar');
            if (!empty($totalbayar))
            {               
                $sisa = $totalharga - $totalbayar;
                    if ($sisa > 0) {                
                        $status = 'Utang';        
                        $set('status', $status);
                    } else {                
                        $status = 'Cash';
                        $set('status', $status);
                    } 
            } else { 
                $sisa = $totalharga;
            }
        } else {
            return [
                Notification::make()
                    ->title('Purchase Denied')
                    ->body('Total harga kosong, input item terlebih dahulu agar totalharga terisi')
                    ->warning()
                    ->send()
            ];
        }        
        $set('sisa', number_format($sisa, 0, '', '.'));                          
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
            'view' => Pages\ViewPurchases::route('/{record}/view'),
        ];
    }
}
