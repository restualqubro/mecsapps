<?php

namespace App\Filament\Resources\Stocks;

use App\Filament\Resources\Stocks\StockinsResource\Pages;
use App\Filament\Resources\Stocks\StockinsResource\RelationManagers;
use App\Models\Products\ProductStocks;
use App\Models\Products\Stockins;
use App\Models\Products\StockCategories;
use App\Models\Products\StockinDetails;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class StockinsResource extends Resource
{
    protected static ?string $model = Stockins::class;

    protected static ?string $navigationGroup = 'Stocks';

    protected static ?string $pluralModelLabel = 'Stockins';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

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
                                    ->label('Kode Stockin')  
                                    ->default(fn() => self::getStockinsCode())                                  
                                    ->readonly()
                                    ->required()                                    ,                                                                
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')                                  
                                    ->options(
                                        StockCategories::where('jenis', '=', 'Stockin')
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('sumber')
                                    ->label('Sumber Stok')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Keterangan')
                                    ->columnSpan('full')
                            ])->columns(3),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Forms\Components\Repeater::make('detailStockin')
                                    ->label('Detail Items')                                                                    
                                    ->relationship('detailStockin')
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('SKU')                                            
                                            ->options(                                                
                                                $stock->mapWithKeys(function (ProductStocks $stock) {
                                                    return [$stock->id => sprintf('%s-%s', $stock->item->code, $stock->code)];
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
                                    ->deletable(true)
                            ]),
                    ])
                    ->columnSpan('full')                    
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
                            StockinDetails::where('stockin_id', $record->id)->delete();
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

    public static function getStockinsCode(): string
    {        
        $date = Carbon::now()->format('my');
        $last = Stockins::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
        if ($last != null) {                                                                                                                                            
            $tmp = substr($last, 8, 4)+1;                                            
            $code = "STI-".$date.sprintf("%03s", $tmp);                                                                            
        } else {
            $code = "STI-".$date."001";
        }
        return $code;
    }   

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockins::route('/'),
            'create' => Pages\CreateStockins::route('/create'),
            'edit' => Pages\EditStockins::route('/{record}/edit'),
        ];
    }
}
