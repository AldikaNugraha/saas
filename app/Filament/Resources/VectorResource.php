<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VectorResource\Pages;
use Filament\Forms\Set;
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
use Filament\Forms\Get;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

function parseGeoJsonProperties(TemporaryUploadedFile $file): array
{
    $filePath = $file->getRealPath();
    if (!file_exists($filePath)) {
        return [];
    }
    
    $fileContents = file_get_contents($filePath);
    $geoJson = json_decode($fileContents, true);

    if (isset($geoJson['type']) && $geoJson['type'] === 'FeatureCollection') {
        $firstFeature = $geoJson['features'][0] ?? null;
        if ($firstFeature && isset($firstFeature['properties'])) {
            return array_keys($firstFeature['properties']);
        }
    }
    return [];
}

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
                    ->afterStateUpdated(function (?TemporaryUploadedFile $state, ?TemporaryUploadedFile $old, Set $set) {
                        if ($state) {
                            $properties = parseGeoJsonProperties($state);
                            $listItems = [];
                            foreach ($properties as $index => $property) {
                                $listItems[$index] = $property;
                            }
                            $set('categorical_properties', $listItems);
                            $set('numerical_properties', $listItems);
                        }
                    })
                    ->label("Input GeoJSON"),
                Select::make("categorical_properties")
                    ->multiple()
                    ->options(fn (Get $get) => $get('categorical_properties') ?? [])
                    ->preload()
                    ->searchable(),
                Select::make("numerical_properties")
                    ->multiple()
                    ->options(fn (Get $get) => $get('numerical_properties') ?? [])
                    ->preload()
                    ->searchable(),
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
                TextColumn::make("name")
                    ->searchable()
                    ->label("Vector type"),
                TextColumn::make("type")
                    ->searchable()
                    ->label("Vector type"),
                TextColumn::make('crs')
                    ->searchable()
                    ->label("CRS"),
                TextColumn::make("num_features")
                    ->numeric()
                    ->label("Num of Vector"),
                TextColumn::make("area")
                    ->label("Area (Km2)"),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'edit' => Pages\EditVector::route('/{record}/edit'),
            'view' => Pages\ViewVectorProperties::route('/{record}/properties'),
            'view-numericals' => Pages\ViewVectorNumericals::route('/{record}/numericals'),
        ];
    }
}
