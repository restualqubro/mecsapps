<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use App\Models\Transactions\Purchase;
use Filament\Widgets\TableWidget as BaseWidget;

class PiutangPurchasesTable extends BaseWidget
{
    use InteractsWithTable;

    protected int | string | array  $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Purchase::query()->where('status', 'Utang'))            
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur'),                
                Tables\Columns\TextColumn::make('supplier.name')                
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Usia')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->diffInDays(\Carbon\Carbon::parse(now()))),                    
                Tables\Columns\TextColumn::make('sisa')
                    ->numeric(decimalPlaces:0)
                    ->label('Sisa Pembayaran')
            ])
            ->defaultSort('code', 'DESC');
    }
}
