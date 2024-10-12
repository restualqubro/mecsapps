<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use App\Models\Transactions\Sale;
use Filament\Widgets\TableWidget as BaseWidget;

class PiutangSalesTable extends BaseWidget
{
    use InteractsWithTable;

    protected static ?string $heading = 'Omzet';

    protected int | string | array  $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Piutang PenSalesan')
            ->query(Sale::query()->where('status', 'Piutang'))            
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur'),                
                Tables\Columns\TextColumn::make('customer.name')                
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Usia')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->diffInDays(\Carbon\Carbon::parse(now()))),                    
                Tables\Columns\TextColumn::make('sisa')
                    ->numeric(decimalPlaces:0)
                    ->label('Sisa Pembayaran')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('contact')
                        ->label('Contact')
                        ->url(function(Sale $record) {                            
                            return 'https://wa.me/+62'.$record->customer->telp."?
                            text=Assalamu'alaikum%20Kami%20dari%20Mecs%20Komputer%20kembali%20mengingatkan%20bahwa%20ada%20nota%20penSalesan%20jatuh%20tempo%20dengan%20kode%20faktur%20".$record->code."%20sejumlah%20".number_format($record->sisa, 0, '', '.');
                        })
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->openUrlInNewTab()
                        ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'teknisi'),
                ])                
            ])            
            ->defaultSort('code', 'DESC');
    }
}
