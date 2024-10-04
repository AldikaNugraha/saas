<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoricalResource\Pages;
use App\Filament\Resources\CategoricalResource\RelationManagers;
use App\Models\Categorical;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoricalResource extends Resource
{
    protected static ?string $model = Categorical::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("project_id")
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship("project","name"),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label("Masukan Nama Data"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Categorical::with("numerical", "project"))
            ->columns([
                TextColumn::make("name")
                    ->searchable()
                    ->label("Nama Data"),
                TextColumn::make("project.name")
                    ->searchable()
                    ->label("Nama Projek"),
                TextColumn::make("sum_num_field")
                    ->state(function (Categorical $record): float {
                        // Clone the main query to avoid modifying the original
                        $query = $record->numerical()->toBase();
                        
                        // Sum the 'num_field' directly from the database
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
