<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RasterResource\Pages;
use App\Filament\Resources\RasterResource\RelationManagers;
use App\Models\Raster;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RasterResource extends Resource
{
    protected static ?string $model = Raster::class;

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
                    ->label("Raster input"),
                Select::make('source')
                    ->required()
                    ->options([
                        'satellite' => 'Satellite',
                        'drone' => 'Drone',
                    ])
                    ->label("Sumber Data"),
                FileUpload::make('path')
                    ->preserveFilenames()
                    ->previewable(false)
                    ->label("Masukan File Tiff"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("project.name")
                    ->searchable()
                    ->label("Nama Projek"),
                TextColumn::make("name")
                    ->searchable()
                    ->label("Raster Name"),
                TextColumn::make("source")
                    ->label("Raster Source"),
                TextColumn::make("band")
                    ->label("Raster Band Count"),
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
            'index' => Pages\ListRasters::route('/'),
            'create' => Pages\CreateRaster::route('/create'),
            'view' => Pages\ViewRaster::route('/{record}'),
            'edit' => Pages\EditRaster::route('/{record}/edit'),
        ];
    }
}
