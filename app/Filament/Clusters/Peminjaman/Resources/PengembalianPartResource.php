<?php

namespace App\Filament\Clusters\Peminjaman\Resources;

use App\Filament\Clusters\Peminjaman;
use App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource\Pages;
use App\Models\Products\PengembalianPart;
use App\Models\Products\PeminjamanPart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;


class PengembalianPartResource extends Resource
{
    protected static ?string $model = PengembalianPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Peminjaman::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->readonly()
                    ->default(fn() => self::getCode()),
                Forms\Components\Select::make('peminjaman_id')
                    ->label('Kode Peminjaman')                                                                                                                                                                                                      
                    ->options(PeminjamanPart::where('status', '!=', 'Kembali')->get()->pluck('code', 'id'))
                    ->required()
                    ->searchable(),                
                Forms\Components\Textarea::make('description')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
            ])
            ->columns([
                'sm' => 1,
                'md' => 1,
                'xl' => 2,
                '2xl' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([                
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('peminjaman.stock.item.name'),
            Tables\Columns\TextColumn::make('peminjaman.qty'),
            Tables\Columns\TextColumn::make('status')
                ->label('Submitted By')   
                ->badge()
                ->colors([
                    'success'   => 'Approved',
                    'danger'    => 'Reject',
                    'gray'      => 'Baru'
                ])            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => $record->status != 'Baru'),
                    Tables\Actions\Action::make('Aprrove')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')                        
                        ->requiresConfirmation()
                        ->modalHeading('Approve')
                        ->color('success')
                        ->modalDescription('Are you sure you\'d like to approve this ?')
                        ->modalSubmitActionLabel('Yes, approve it')
                        ->action(function (PengembalianPart $record) {
                            $record->update([
                                'status'        => 'Approve',
                                'approval_id'   => auth()->id()
                            ]);
                            PeminjamanPart::where('id', $record->peminjaman_id)
                                            ->update([
                                                'status'    => 'Kembali'
                                            ]);
                        })
                        ->hidden(fn(PengembalianPart $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\Action::make('Reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->modalHeading('Reject')
                        ->modalDescription('Are you sure you\'d like to reject this ?')
                        ->modalSubmitActionLabel('Yes, reject it')
                        ->action(fn (PengembalianPart $record) => $record->update([
                            'status'        => 'Reject',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(PengembalianPart $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn($record) => $record->status != 'Baru'),
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
        $now = Carbon::now()->format('my');
        $last = PeminjamanPart::whereRaw("MID(code, 5, 4) = $now")->max('code');        
        if ($last) 
        {                                                                                            
            $tmp = substr($last, 8, 2)+1;
            $code = "MPJ-".$now.sprintf("%02s", $tmp);                                                                            
        } else {
            $code =  "MPJ-".$now."01";
        }        
        return $code;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengembalianParts::route('/'),            
        ];
    }
}
