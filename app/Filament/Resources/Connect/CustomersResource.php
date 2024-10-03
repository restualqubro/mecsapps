<?php

namespace App\Filament\Resources\Connect;

use App\Filament\Resources\Connect\CustomersResource\Pages;
use App\Models\Connect\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $navigationGroup = 'Connect';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('code')
                    ->required()
                    ->default(function () {                            
                            $date = Carbon::now()->format('my');                            
                            $record = Customers::latest('code')->whereRaw("MID(code, 4, 4) = ".$date)->first();                                       
                            if ($record === null) {
                                return "CST".$date."001";                                
                            } else {
                                $tmp = Str::substr($record->code, 7, 3)+1;
                                $code = sprintf("%03s", $tmp);
                                return "CST".$date.$code;
                            }
                        }
                    ),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('telp')
                    ->label('No HP/WA')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->prefix('+62')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipe Customer')
                    ->options([
                        'Customer'  => 'Customer',
                        'Reseller'  => 'Reseller',
                        'Twincom'   => 'Twincom'
                    ])
                    ->required(),
                Forms\Components\Textarea::make('address')                    
                    ->label('Alamat')    
                    ->required()                  
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Customer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Customer')
                    ->limit(20)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('contact')
                        ->hiddenLabel()
                        ->tooltip('Contact')
                        ->url(function($record) {
                            
                            // return dd($record);
                            return 'https://wa.me/+62'.$record->telp;
                        })
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->openUrlInNewTab(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
        ];
    }
}
