<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceSelesaiResource\Pages;
use App\Filament\Resources\Services\ServiceSelesaiResource\RelationManagers;
use App\Models\Services\ServiceSelesai;
use App\Models\Services\ServiceData;
use App\Models\Transactions\Sale;
use App\Models\Products\ProductStocks;
use App\Models\Services\ServiceCatalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;

class ServiceSelesaiResource extends Resource
{
    protected static ?string $model = ServiceSelesai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 4;

    protected static ?string $pluralModelLabel = 'Service Selesai';

    protected static ?string $slug = 'service-selesai';

    public static function form(Form $form): Form
    {
        $stock = ProductStocks::get()->where('product.category.name', '!=', 'COMPONENT');
        $component = ProductStocks::get()->where('product.category.name', 'COMPONENT');
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
                                            Forms\Components\TextInput::make('teknisi')                                                
                                                ->required()
                                                ->default(fn() => auth()->user()->name)
                                                ->disabled()
                                                ->columnSpan([
                                                    'md' => 2
                                                ]),                                                                                                            
                                            Forms\Components\Select::make('reference')
                                                ->label('Referensi Faktur Jual')    
                                                ->options(Sale::all()->pluck('code', 'id'))  
                                                ->searchable()                                                                                          
                                                ->columnSpan(2),                                                  
                                            Forms\Components\Select::make('service_id')
                                                ->label('Kode Service')
                                                ->options(
                                                    ServiceData::all()->where('status', 'Proses')->pluck('code', 'id')
                                                )
                                                ->live()
                                                ->afterStateUpdated(function($state, Forms\Get $get, Forms\Set $set) {
                                                    $service = ServiceData::find($state);
                                                    if ($service) 
                                                    {
                                                        $set('name', $service->customer->name);
                                                        $set('merk', $service->merk);
                                                        $set('seri', $service->seri);
                                                    }
                                                })
                                                ->columnSpan(2)
                                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                                
                                        ])
                                        ->columns(6),                                                 
                                    Forms\Components\Group::make()
                                        ->schema([                                        
                                            Forms\Components\TextInput::make('name')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('merk')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('seri')
                                                ->disabled()
                                        ])
                                        ->columns(3)
                                ]),  
                            ]),   
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Service Catalog'),
                                Repeater::make('detailService')
                                    ->label('Detail Catalog Service')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('servicecatalog_id')
                                            ->label('Kode Catalog')                                                                                        
                                            ->options(ServiceCatalog::all()->pluck('name', 'id'))                                                                                            
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                                return collect($get('../*.servicecatalog_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            }) 
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $service = ServiceCatalog::find($state);
                                                if ($service) {                                                    
                                                    $set('biaya', $service->biaya_max);                                                                  
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 4
                                            ]),                                  
                                        Forms\Components\TextInput::make('biaya')
                                            ->label('Biaya')
                                            ->disabled() 
                                            ->dehydrated()                                           
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                                                    
                                        Forms\Components\TextInput::make('catalog_disc')                                            
                                            ->label('Discount')
                                            ->numeric()                                             
                                            ->default(0)  
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 1
                                            ]),
                                        Forms\Components\TextInput::make('catalog_qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()                                                                                                                                                                                                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $servicecatalog_id = $get('servicecatalog_id');
                                                    $catalog = ServiceCatalog::find($servicecatalog_id);
                                                    if($catalog)
                                                    {                                                        
                                                        $disc = $get('service_disc');                                                                                                               
                                                        $jumlah = $get('service_qty') * ($catalog->biaya_max - $disc) ;
                                                        $set('service_jumlah', number_format($jumlah, 0, '', '.'));
                                                    }                                                                                                        
                                                }
                                            ),                                                                              
                                        Forms\Components\TextInput::make('service_jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalService($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalService($get, $set)),
                                    ) 
                                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                        $data['catalog_disc'] = $data['catalog_disc'];
                                        $data['catalog_qty'] = $data['catalog_qty'];
                                        $data['service_jumlah'] = $data['catalog_qty'] * ($data['biaya'] - $data['catalog_disc']);
                                 
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
                                Forms\Components\Placeholder::make('Service Component'),
                                Repeater::make('detailComponent')
                                    ->label('Detail Component')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('Kode Component')                                                                                        
                                            ->options($component->mapWithKeys(function (ProductStocks $component) {
                                                return [$component->id => sprintf('%s-%s | %s', $component->item->code, $component->code, $component->item->name)];
                                            }))                                                                                                                                        
                                            ->searchable()
                                            ->reactive()
                                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                                return collect($get('../*.stock_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            }) 
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $component = ProductStocks::find($state);
                                                if ($component) {                                                    
                                                    $set('hbeli', $component->hbeli);                                                                  
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                  
                                        Forms\Components\TextInput::make('hbeli')
                                            ->label('Harga Beli')
                                            ->disabled()                  
                                            ->dehydrated()                          
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                                                                                            
                                        Forms\Components\TextInput::make('component_qty') 
                                            ->label('Qty')   
                                            ->numeric()                                                                                                                                                                                                                                                                           
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $stock_id = $get('stock_id');
                                                    $component = ProductStocks::find($stock_id);
                                                    if($component)
                                                    {                                                                                                                
                                                        $qty = $get('component_qty');                                                        
                                                        $jumlah = $qty * $component->hbeli;
                                                        $set('component_jumlah', number_format($jumlah, 0, '', '.'));
                                                    }                                                                                                        
                                                }
                                            ),                                                                              
                                        Forms\Components\TextInput::make('component_jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalComponent($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalComponent($get, $set)),
                                    )
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                                    ->defaultItems(0)
                                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {                                        
                                        $data['component_qty'] = $data['component_qty'];
                                        $data['hbeli'] = $data['hbeli'];
                                        $data['component_jumlah'] = $data['component_qty'] * $data['hbeli'];
                                 
                                        return $data;
                                    })
                            ]),
                    Forms\Components\Card::make()                                                                                              
                        ->schema([  
                            Forms\Components\Group::make()
                                ->schema([                                       
                                    Forms\Components\TextInput::make('subtotal_service')
                                        ->label('Subtotal Service')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),                      
                                    Forms\Components\TextInput::make('totaldiscount_service')
                                        ->label('Total Discount Service')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),                                                       
                                    Forms\Components\TextInput::make('subtotal_component')
                                        ->label('Total Komponen')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),                                                                                                                           
                                    Forms\Components\TextInput::make('total')
                                        ->label('Total')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),
                                ])->columns(3),                                        
                            ])                           
                ])->columnSpan('full')            
        ]);
    }  

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.code')
                    ->label('KODE SERVICE')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.customer.name')
                    ->label('NAMA CUSTOMER'),
                Tables\Columns\TextColumn::make('subtotal_service')
                    ->label('SUBTOTAL SERVICE')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('totaldiscount_service')
                    ->label('TOTAL DISCOUNT SERVICE')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total')
                    ->label('TOTAL')
                    ->money('IDR'),
                
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),                        
                    Tables\Actions\EditAction::make(),
                        // ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'customer_service'),
                    Tables\Actions\Action::make('print')                    
                        ->url(fn ($record) => 'print/selesaireceipt/'.$record->id)
                        ->color('warning')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab(),                
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
                Section::make('Service Details')
                    ->schema([
                        TextEntry::make('service.code')
                            ->label('Kode Service')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('updated_at')
                            ->label('Last Updated'), 
                        TextEntry::make('service.customer.name')
                            ->label('Nama Customer'), 
                        TextEntry::make('service.merk')
                            ->label('Merk/Brand'),
                        TextEntry::make('service.seri')
                            ->label('Seti/Tipe'),                        
                        TextEntry::make('service.keluhan')
                            ->label('Keluhan'), 
                        TextEntry::make('subtotal_service')
                            ->label('Subtotal Service')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                                                                                                 
                        TextEntry::make('totaldiscount_service')
                            ->label('Total Discount')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),    
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('IDR')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('subtotal_component')
                            ->label('Total Component')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('sale.code')
                            ->label('Referensi Faktur Penjualan')                            
                            ->weight(FontWeight::Bold),    
                ])->columns(2),                                                             
                RepeatableEntry::make('detailService')
                    ->label('Detail Catalog Service')
                    ->schema([                                                                                                                                                                          
                        TextEntry::make('catalog.name')                        
                            ->label('Detail Perbaikan'),
                        TextEntry::make('biaya')
                            ->money('IDR')
                            ->label('Biaya'),
                        TextEntry::make('service_qty')
                            ->label('Qty'),
                        TextEntry::make('service_disc')
                            ->money('IDR')
                            ->label('Discount')
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2),
                RepeatableEntry::make('detailComponent')
                    ->label('Detail Component Service')
                    ->schema([
                        TextEntry::make('stock.item.code')                        
                            ->label('Detail Perbaikan'),                                                                                                                                                                                                 
                        TextEntry::make('stock.item.name')                        
                            ->label('Detail Perbaikan'),                       
                        TextEntry::make('component_qty')                        
                            ->label('Qty'),
                        TextEntry::make('stock.item.hjual')                        
                            ->money('IDR')
                            ->label('Harga'),
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2),
                RepeatableEntry::make('sale.saleDetails')                    
                    ->label('Detail Penjualan')
                    ->schema([
                        TextEntry::make('productStocks.fullcode')                        
                            ->label('Code'),
                        TextEntry::make('productStocks.item.name')                        
                            ->label('Items'),
                        TextEntry::make('productStocks.item.sale_warranty'),
                        TextEntry::make('qty')
                            ->label('Qty'),  
                        TextEntry::make('hjual')
                            ->money('IDR')
                            ->label('Harga Satuan'),
                        TextEntry::make('disc')
                            ->money('IDR')
                            ->label('Discount'),                                          
                        TextEntry::make('jumlah')                            
                            ->money('IDR')                            
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2)        
            ]);
    }

    public static function updateTotalService(Forms\Get $get, Forms\Set $set): void
    {        
        $selectedCatalog = collect($get('detailService'))->filter(fn($item) => !empty($item['service_qty']) && !empty($item['biaya']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedCatalog as $item) {
            $subtotal = $subtotal +  $item['service_qty'] * $item['biaya'] ;
            $totaldiscount += $item['service_disc'] * $item['service_qty'];      
            $total = $subtotal - $totaldiscount;
        }                                          
        $set('subtotal_service', number_format($subtotal, 0, '', '.'));
        $set('totaldiscount_service', number_format($totaldiscount, 0, '', '.'));      
        $set('total', number_format($total, 0, '', '.'));            
    }

    public static function updateTotalComponent(Forms\Get $get, Forms\Set $set): void
    {        
        $selectedProducts = collect($get('detailComponent'))->filter(fn($item) => !empty($item['component_qty']) && !empty($item['component_hbeli']));                
        $subtotal = 0;                     
        foreach($selectedProducts as $item) {
            $subtotal += $item['component_hbeli'] * $item['component_qty'];              
        }                                      
        $set('subtotal_component', number_format($subtotal, 0, '', '.'));                   
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceSelesais::route('/'),
            'create' => Pages\CreateServiceSelesai::route('/create'),
            'edit' => Pages\EditServiceSelesai::route('/{record}/edit'),
            'view' => Pages\ViewServiceSelesai::route('/{record}/view')
        ];
    }
}
