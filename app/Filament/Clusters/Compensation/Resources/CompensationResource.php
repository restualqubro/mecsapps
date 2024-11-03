<?php

namespace App\Filament\Clusters\Compensation\Resources;

use App\Filament\Clusters\Compensation;
use App\Filament\Clusters\Compensation\Resources\CompensationResource\Pages;
use App\Filament\Clusters\Compensation\Resources\CompensationResource\RelationManagers;
use App\Models\Finance\Compensation as Data;
use App\Models\Finance\CompensationCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CompensationResource extends Resource
{
    protected static ?string $model = Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $pluralModelLabel = 'Kompensasi';

    protected static ?string $cluster = Compensation::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Kompensasi')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(CompensationCategories::all()->pluck('name', 'id')),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columns(2)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nominal'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50)
            ])
            ->filters([                                
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('by Category')
                    ->options(CompensationCategories::all()->pluck('name', 'id'))
                    ->multiple(),                
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
            'index' => Pages\ListCompensation::route('/'),            
        ];
    }
}
