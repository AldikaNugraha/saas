<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VectorResource\Pages;
use App\Filament\Resources\VectorResource\RelationManagers;
use App\Jobs\GeojsonJob;
use App\Models\Vector;
use Exception;
use App\Rules\GeoJson;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\RequestException;

class VectorResource extends Resource
{
    protected static ?string $model = Vector::class;

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
                TextInput::make("name")
                    ->label("Vector Name")
                    ->required(),
                FileUpload::make('path')
                    ->rules([new GeoJson()])
                    ->moveFiles()
                    ->preserveFilenames()
                    ->previewable(false)
                    ->label("Input GeoJSON"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function (Builder $query) {
            // Assuming `user_id` is the foreign key in the projects table
            $query->whereHas('project', function ($projectQuery) {
                $projectQuery->where('user_id', auth()->user()->id);
            });
        })
            ->columns([
                TextColumn::make("project.name")
                    ->searchable()
                    ->label("Project Name"),
                TextColumn::make("type")
                    ->label("Vector type"),
                TextColumn::make("num_features")
                    ->label("Num of Vector"),
                TextColumn::make("area")
                    ->label("Area (Km2)"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->before(function (Model $record) {
                        try {
                            GeojsonJob::dispatch($record,null,true);
                        } catch (Exception $e) {
                            // Handle the exception and output the error message
                            dd($e->getMessage());
                        }
                }),
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
