<?php

namespace App\Filament\Resources\Stocks;

use App\Filament\Resources\Stocks\StockoutsResource\Pages;
use App\Filament\Resources\Stocks\StockoutsResource\RelationManagers;
use App\Models\Products\ProductStocks;
use App\Models\Products\StockCategories;
use App\Models\Products\StockoutDetails;
use App\Models\Products\Stockouts;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class StockoutsResource extends Resource
{
    protected static ?string $model = Stockouts::class;

    protected static ?string $navigationGroup = 'Stocks';

    protected static ?string $pluralModelLabel = 'Stockout';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square';

    public static function form(Form $form): Form
    {
        $stock = ProductStocks::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Kode Stockout')
                                    ->default(fn() => self::getStockoutCode())
                                    ->readonly()
                                    ->required(),                                                              
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')                                  
                                    ->options(StockCategories::where('jenis', '=', 'Stockout')->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),                                
                                Forms\Components\Textarea::make('description')
                                    ->label('Keterangan'),
                            ])->columns(3),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Forms\Components\Repeater::make('detailStockout')
                                    ->label('Detail Items')                                                                    
                                    ->relationship()
                                    ->schema([                                                                                
                                        Forms\Components\Select::make('stock_id')
                                            ->label('SKU')                                                                                        
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
                                                    $set('name', $stock->item->name);                                                    
                                                }
                                            })
                                            ->columnSpan([
                                                'md' => 3
                                            ]),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Item')                                                                                 
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan([
                                                'md' => 5   
                                            ]),                                          
                                        Forms\Components\TextInput::make('qty')                                            
                                            ->numeric()                                            
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                        
                                    ])
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')            
            ])
            ->filters([
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
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Stockins')
                        ->modalDescription('You sure want deleted this data ? if you delete it, 
                                            item details will also deleted')   
                        ->action(function($record) {
                            StockoutDetails::where('stockout_id', $record->id)->delete();
                            $record->delete();                    
                        })
                        ->after(function () {                        
                            Notification::make()
                                ->title('Deleted successfully')
                                ->icon('heroicon-o-check-circle')
                                ->iconColor('success')
                                ->send();
                        }),
                ])                
            ]);
    }

    public static function getStockoutCode(): string
    {
        $date = Carbon::now()->format('my');
        $last = Stockouts::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                    
            $tmp = substr($last, 8, 4)+1;            
            $code =  "STO-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            $code = "STO-".$date."001";
        }
        
        return $code;
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockouts::route('/'),
            'create' => Pages\CreateStockouts::route('/create'),
            'edit' => Pages\EditStockouts::route('/{record}/edit'),
        ];
    }
}
