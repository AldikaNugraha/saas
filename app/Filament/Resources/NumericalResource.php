<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NumericalResource\Pages;
use App\Filament\Resources\NumericalResource\RelationManagers;
use App\Models\Numerical;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NumericalResource extends Resource
{
    protected static ?string $model = Numerical::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("categorical_id")
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship("categorical","name"),
                TextInput::make('name')
                    ->required()
                    ->label("Numerical Name"),
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
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Numerical::with(relations: "categorical"))
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNumericals::route('/'),
            'create' => Pages\CreateNumerical::route('/create'),
            'edit' => Pages\EditNumerical::route('/{record}/edit'),
        ];
    }
}
