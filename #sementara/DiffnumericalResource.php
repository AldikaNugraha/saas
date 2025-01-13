<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiffnumericalResource\Pages;
use App\Filament\Resources\DiffnumericalResource\RelationManagers;
use App\Models\Diffnumerical;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DiffnumericalResource extends Resource
{
    protected static ?string $model = Diffnumerical::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label("Numerical Name"),
                Select::make("categorical_id")
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship("categorical","name"),
                DateTimePicker::make('created_at')
                                ->format('d-m-Y')
                                ->weekStartsOnMonday()
                                ->timezone('Asia/Jakarta')
                                ->maxDate(now())
                                ->label("Tanggal"),
                TextInput::make('num_field')
                    ->required()
                    ->maxLength(255)
                    ->label("Enter Numeric Value"),
                Repeater::make("numericalType")
                    ->relationship()
                    ->schema([
                        Select::make("type_id")
                            ->relationship("type","name")
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Diffnumerical::with(relations: "categorical"))
            ->columns([
                TextColumn::make("categorical.project.name")
                    ->searchable()
                    ->label("Project Name"),
                TextColumn::make("categorical.name")
                    ->searchable()
                    ->label("Categorical Name"),
                TextColumn::make("num_field")
                    ->numeric()
                    ->sortable()
                    ->label("Numerical Field"),
                TextColumn::make("numericalType.type.name")
                    ->searchable()
                    ->label("Type Name"),
                TextColumn::make("created_at")
                    ->date()
                    ->label("Created At"),
                
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiffnumericals::route('/'),
            'create' => Pages\CreateDiffnumerical::route('/create'),
            'edit' => Pages\EditDiffnumerical::route('/{record}/edit'),
        ];
    }
}
