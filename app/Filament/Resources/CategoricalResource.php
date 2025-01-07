<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoricalResource\Pages;
use App\Filament\Resources\CategoricalResource\Widgets\CustomChart;
use App\Models\Categorical;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Assuming `user_id` is the foreign key in the projects table
                $query->whereHas('vector.project', function ($projectQuery) {
                    $projectQuery->where('user_id', auth()->user()->id);
                });
            })
            ->columns([
                TextColumn::make("name")
                    ->searchable()
                    ->label("Nama Data"),
                TextColumn::make("vector.name")
                    ->searchable()
                    ->label("Vector Name"),
                TextColumn::make("sum_num_field")
                    ->state(function (Categorical $record): float {
                        $query = $record->numerical()->toBase();
                        return (clone $query)->sum('num_field');
                    })
                    ->label("Sum Numerical Field"),
                
            ])
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
