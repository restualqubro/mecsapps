<?php

namespace App\Filament\Clusters\Sales\Resources;

use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Sales\Resources\SalePreordersResource\Pages;
use App\Filament\Clusters\Sales\Resources\SalePreordersResource\RelationManagers;
use App\Models\Transactions\SalePreorders;
use App\Models\Connect\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class SalePreordersResource extends Resource
{
    protected static ?string $model = SalePreorders::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';
    
    protected static ?string $pluralModelLabel = 'Preorders';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;    

    protected static ?string $cluster = Sales::class;

    public static function form(Form $form): Form
    {        
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([                        
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('code')
                                            ->label('Faktur Preorder')
                                            ->default(fn() => self::getCode())
                                            ->readonly()
                                            ->required()
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan([
                                                'md' => 3
                                            ]),                                                                                                                                     
                                        Forms\Components\Select::make('customer_id')
                                            ->label('Customer')
                                            ->required()
                                            ->options(Customers::all()->pluck('name','id'))
                                            ->columnSpan([
                                                'md' => 3
                                            ]),
                                        Forms\Components\TextInput::make('nominal')                                            
                                            ->required()
                                            ->numeric()                                            
                                            ->label('Nominal DP')
                                            ->columnSpan([
                                                'md' => 3
                                            ]),                                                               
                                        Forms\Components\TextInput::make('estimasi')
                                            ->label('Estimasi Waktu')
                                            ->required()                                            
                                            ->columnSpan([
                                                'md' => 3
                                            ]),                                                                                  
                                    ])->columns(6),                                
                                Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Textarea::make('description')
                                        ->label('Description / Keterangan (Masukkan Item dan Detail Item Preorder)')
                                        ->rows(1)                                                                    
                                ])
                                ->columns('full')
                            ])->columnSpan(6),                                                                               
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Preorder')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal DP')
                    ->money('IDR'),
                    Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Preorder')
                    ->date(),
                Tables\Columns\TextColumn::make('estimasi')                    
                    ->suffix(' Hari'),
                Tables\Columns\TextColumn::make('status')                    
                    ->badge()
                    ->colors([
                        'danger'    => 'Cancel',
                        'success'   => 'Selesai',
                        'gray'      => 'Baru'
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('print')                    
                        ->url(fn ($record) => '/print/fakturpreorder/'.$record->id)
                        ->color('warning')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab(),    
                    Tables\Actions\EditAction::make()
                        ->hidden(fn(SalePreorders $record) => $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel')
                        ->color('danger')
                        ->icon('heroicon-o-no-symbol')      
                        ->requiresConfirmation()                  
                        ->action(fn(SalePreorders $record) => $record->find($record->id)->update(['status' => 'Cancel']))
                        ->hidden(fn(SalePreorders $record) => $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn(SalePreorders $record) => $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                ])
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getCode(): string
    {
        $date = Carbon::now()->format('my');
        $last = SalePreorders::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                            
            $tmp = substr($last, 8, 4)+1;
            $code =  "FPO-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            $code =  "FPO-".$date."001";
        }

        return $code;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalePreorders::route('/'),
            'create' => Pages\CreateSalePreorders::route('/create'),
            'edit' => Pages\EditSalePreorders::route('/{record}/edit'),
        ];
    }
}
