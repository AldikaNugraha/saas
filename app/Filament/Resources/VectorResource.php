<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VectorResource\Pages;
use App\Filament\Resources\VectorResource\RelationManagers;
use App\Models\Vector;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VectorResource extends Resource
{
    protected static ?string $model = Vector::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("categorical_id")
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship("categorical","name"),
                FileUpload::make('path')
                    ->multiple()
                    ->preserveFilenames()
                    ->previewable(false)
                    ->label("Masukan File GeoJSON"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("categorical.name")
                    ->searchable()
                    ->label("Nama Data"),
                TextColumn::make("type")
                    ->label("Tipe Vektor"),
                TextColumn::make("num_features")
                    ->label("Jumlah Vektor"),
                TextColumn::make("area")
                    ->label("Luas (Km2)"),
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
            'index' => Pages\ListVectors::route('/'),
            'create' => Pages\CreateVector::route('/create'),
            'view' => Pages\ViewVector::route('/{record}'),
            'edit' => Pages\EditVector::route('/{record}/edit'),
        ];
    }
}
