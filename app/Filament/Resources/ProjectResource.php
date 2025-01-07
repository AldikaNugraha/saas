<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label("Masukan Nama Projek"),
                Textarea::make('description')
                    ->autosize()
                    ->minLength(1)
                    ->maxLength(1024)
                    ->label("Deskripsi Projek")
                    ->required(),
                TextInput::make('comodity')
                    ->required()
                    ->maxLength(255)
                    ->label("Komoditas"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => 
                $query->where('user_id', auth()->user()->id)
            )
            ->columns([
                TextColumn::make("name")
                    ->searchable()
                    ->label("Project Name"),
                TextColumn::make("comodity")
                    ->searchable()
                    ->label("Project Comodity"),
                TextColumn::make("vector_count")
                    ->counts("vector")
                    ->numeric()
                    ->label("Vector Count"),
                TextColumn::make("raster_count")
                    ->counts("raster")
                    ->numeric()
                    ->label("Raster Count"),
                TextColumn::make("status")
                    ->sortable()
                    ->label("Project Status"),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ProjectMap::route('/{record}/map'),
        ];
    }
}
