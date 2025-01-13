<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoricalResource\Pages;
use App\Filament\Resources\CategoricalResource\Widgets\CustomChart;
use App\Models\Categorical;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Column;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
// TextColumn::make("sum_num_field")
                //     ->state(function (Categorical $record): float {
                //         $query = $record->numerical()->toBase();
                //         return (clone $query)->sum('num_field');
                //     })
                //     ->label("Sum Numerical Field"),
class CategoricalResource extends Resource
{
    protected static ?string $model = Categorical::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("vector_id")
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship("vector","name"),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label("Masukan Nama Data"),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Cache the JSON keys with user-specific scope
        $jsonKeys = cache()->remember('columns' . auth()->id(), 3600, function() {
            $sampleRecord = Categorical::whereHas('vector.project', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->first();

            if ($sampleRecord && isset($sampleRecord->columns)) {
                $jsonData = json_decode($sampleRecord->columns, true);
                return is_array($jsonData) ? array_keys($jsonData) : [];
            }
            
            return [];
        });

        // Define static columns  
        $columns = [  
            TextColumn::make("name")  
                ->searchable()  
                ->label("Nama Data"),  
            TextColumn::make("vector.name")  
                ->searchable()  
                ->label("Vector Name"),  
        ];  
  
        // Add dynamic columns based on JSON keys  
        foreach ($jsonKeys as $key) {
            $columns[] = TextColumn::make("columns.{$key}")
                ->label(ucfirst(str_replace('_', ' ', $key)))
                ->searchable()
                ->sortable()
                ->toggleable()
                ;
        }
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('vector.project', function ($projectQuery) {
                    $projectQuery->where('user_id', auth()->user()->id);
                });
            })
            ->columns($columns)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getWidgets(): array
{
    return [
        CategoricalResource\Widgets\NumericalOverview::class,
        CustomChart::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategoricals::route('/'),
            'create' => Pages\CreateCategorical::route('/create'),
            'edit' => Pages\EditCategorical::route('/{record}/edit'),
        ];
    }
}
