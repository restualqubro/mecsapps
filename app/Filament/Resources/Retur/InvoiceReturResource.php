<?php

namespace App\Filament\Resources\Retur;

use App\Filament\Resources\Retur\InvoiceReturResource\Pages;
use App\Models\Retur\InvoiceRetur;
use App\Models\Transactions\Invoices;
use App\Models\Services\SelesaiDetailCatalogs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class InvoiceReturResource extends Resource
{
    protected static ?string $model = InvoiceRetur::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $slug = 'serv';

    protected static ?string $navigationGroup = 'Retur';
    
    protected static ?string $pluralModelLabel = 'Retur Service';

    public static function form(Form $form): Form
    {
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
                                                ->label('Faktur Retur')
                                                ->default(fn() => self::GetCode())
                                                ->readonly()
                                                ->required()
                                                ->columnSpan([
                                                    'md' => 2
                                                ]),                                
                                            Forms\Components\DatePicker::make('tanggal')
                                                ->default(now())                                                
                                                ->disabled()
                                                ->columnSpan([
                                                    'md' => 2
                                                ]),                                                                                                               
                                            Forms\Components\Select::make('invoice_id')
                                                ->required()
                                                ->options(Invoices::all()->pluck('code', 'id'))
                                                ->searchable()
                                                ->columnSpan([
                                                    'md' => 2
                                                ]),                                                                                                               
                                        ])->columns(6)
                            ])
                        ]),                                                                                                                                                   
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Placeholder::make('Products'),
                            Forms\Components\Repeater::make('detailRetur')
                                ->label('Detail Items')                                                                    
                                ->relationship()
                                ->collapsible()
                                ->schema([                                        
                                    Forms\Components\Select::make('selesaidetailcatalog_id')
                                    ->label('Items Service')
                                    ->options(
                                        function (Forms\Get $get) {
                                            $invoiceid = $get('../../invoice_id');                                                
                                            if ($invoiceid)
                                            {                 
                                                $selesai = Invoices::find($invoiceid);
                                                $items = SelesaiDetailCatalogs::where('selesai_id', $selesai->selesai_id)
                                                            ->get()
                                                            ->pluck('catalog.name', 'id');
                                                return $items;                                                
                                            }
                                        }
                                    )                                                                                           
                                        ->required()
                                        ->searchable()                                        
                                        ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                            return collect($get('../*.servicecatalog_id'))
                                                ->reject(fn($id) => $id == $state)
                                                ->filter()
                                                ->contains($value);
                                        }) 
                                        ->afterStateUpdated(function($state, callable $set, callable $get) {
                                            $invoiceid = $get('../../invoice_id');                                                
                                            if ($invoiceid)
                                            {                                                              
                                                // $selesai = Invoices::find($invoiceid)->first();
                                                $details = SelesaiDetailCatalogs::find($state);                                                                                                
                                                if ($details) {                                                               
                                                    $set('biaya', $details->biaya);                                                
                                                    $set('disc', $details->catalog_disc);                                                                                                   
                                                }
                                            }                                            
                                            
                                        })                                           
                                        ->columnSpan([
                                            'md' => 5
                                        ]),                                                                                                             
                                    Forms\Components\TextInput::make('biaya')                                            
                                        ->label('Biaya')
                                        ->disabled() 
                                        ->dehydrated()                                           
                                        ->columnSpan([
                                            'md' => 1
                                        ]),                                                                            
                                    Forms\Components\TextInput::make('disc')                                            
                                        ->label('Discount')
                                        ->disabled()     
                                        ->dehydrated()
                                        ->minValue(0)                                                                          
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
                                        ->maxValue(function (SelesaiDetailCatalogs $item, Forms\Get $get): int
                                            {      
                                                $invoiceid = $get('../../invoice_id');                                                
                                                if ($invoiceid)
                                                {                                                 
                                                $items = $item->find($get('selesaidetailcatalog_id')) ;                                                                                                                                        
                                                if ($items) {
                                                    $max = $items->catalog_qty;
                                                    return $max;
                                                }
                                            }                                                                                                                                                                                         
                                            })
                                        ->live(onBlur:true)
                                        ->afterStateUpdated(
                                            function (Forms\Get $get, Forms\Set $set) {                                                
                                                $qty = $get('qty');
                                                $biaya = $get('biaya');
                                                $disc = $get('disc');
                                                $jumlah = $qty * ($biaya - $disc);                                                                                                        
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
                                    fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalHarga($get, $set)),
                                )                                
                                ->defaultItems(1)
                                ->columns([
                                    'md' => 10
                                ])
                                ->columnSpan('full')
                        ]),
                    Forms\Components\Card::make()                                                                                              
                        ->schema([        
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->disabled()
                                ->dehydrated(),
                            Forms\Components\TextInput::make('totaldiscount')
                                ->label('Total Biaya Retur')                                
                                ->disabled()
                                ->dehydrated(),                                                                                                         
                            Forms\Components\TextInput::make('totalbiaya')
                                ->label('Total Biaya Retur')                                
                                ->disabled()
                                ->dehydrated(),                            
                            ])->columns(3),
                ])->columnSpan('full')    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice.selesai.service.customer.name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function updateTotalHarga(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('detailRetur'))->filter(fn($item) => !empty($item['qty']) && !empty($item['biaya']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal += $item['biaya'] * $item['qty'];
            $totaldiscount += $item['disc'] * $item['qty'];            
        }                              
        $total = $subtotal - $totaldiscount;
        // Update the state with the new values
        $set('totalbiaya', number_format($total, 0, '', '.'));                
        

    }

    public static function getCode(): string
    {
        $date = Carbon::now()->format('my');
        $last = InvoiceRetur::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                            
            $tmp = substr($last, 8, 4)+1;
            $code =  "FRS-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            $code =  "FRS-".$date."001";
        }

        return $code;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceReturs::route('/'),
            'create' => Pages\CreateInvoiceRetur::route('/create'),
            'edit' => Pages\EditInvoiceRetur::route('/{record}/edit'),
        ];
    }
}
