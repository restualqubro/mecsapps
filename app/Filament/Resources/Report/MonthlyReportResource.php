<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\MonthlyReportResource\Pages;
use App\Filament\Resources\Report\MonthlyReportResource\RelationManagers;
use App\Models\Report\MonthlyReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonthlyReportResource extends Resource
{
    protected static ?string $model = MonthlyReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('month')
                    ->label('Month')
                    ->options([
                        'Januari'   => 'Januari',
                        'Februari'  => 'Februari',
                        'Maret'     => 'Maret',
                        'April'     => 'April',
                        'Mei'       => 'Mei',
                        'Juni'      => 'Juni',
                        'Juli'      => 'Juli',
                        'Agustus'   => 'Agustus',
                        'September' => 'September',
                        'Oktober'   => 'Oktober',
                        'November'  => 'November', 
                        'Desember'  => 'Desember'
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('year')
                    ->label('Year')
                    ->options(array_combine(range(2024, 2030), range(2024, 2030)))
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('date_from')
                    ->label('Date From')
                    ->required(),
                Forms\Components\DatePicker::make('date_to')
                    ->label('Date to')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->columnSpan('full')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('month'),                    
                Tables\Columns\TextColumn::make('year'),                
                Tables\Columns\TextColumn::make('date_from')
                    ->date(),
                Tables\Columns\TextColumn::make('date_to')
                    ->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->url(fn ($record) => '/print/monthlyreport/'.$record->id)            
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonthlyReports::route('/'),            
        ];
    }
}
