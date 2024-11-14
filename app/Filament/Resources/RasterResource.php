<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RasterResource\Pages;
use App\Filament\Resources\RasterResource\RelationManagers;
use App\Jobs\RasterJob;
use App\Models\Raster;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Forms\Get;
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
use Exception;

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
                Select::make('source')
                    ->required()
                    ->options([
                        'satellite' => 'Satellite',
                        'drone' => 'Drone',
                    ])
                    ->reactive()
                    // ->afterStateUpdated(function (Set $set, $state) {
                    //     if ($state == "satellite"){
                    //         $set('status', "draft");
                    //     } elseif($state == "drone") {
                    //         $set('status', "reviewing");
                    //     }
                    // })
                    ->label("Sumber Data"),                 
                TextInput::make('name')
                    ->required()
                    ->label("Raster name"),
                Select::make('sattelite_source')
                    ->options([
                        'cop-dem-glo-90' => 'Copernicus DEM GLO-30',
                        'sentinel-2-l1c' => 'Sentinel-2 Level-1C',
                        'sentinel-2-l2a' => 'Sentinel-2 Level-2A',
                        'sentinel-2-c1-l2a' => 'Sentinel-2 Collection 1 Level-2A',
                        'landsat-c2-l2' => 'Landsat Collection 2 Level-2',
                        'sentinel-2-pre-c1-l2a' => 'Sentinel-2 Pre-Collection 1 Level-2A',
                    ])
                    ->label("Sattelite Source")
                    ->searchable()
                    ->hidden(fn (Get $get) => $get('source') === 'drone')
                    ->required(fn (Get $get) => $get('source') === 'satellite'),
                Toggle::make('do_monitoring')
                    ->label("Create Monitoring using this Collection ")
                    ->hidden(fn (Get $get) => $get('source') === 'drone')
                    ->required(fn (Get $get) => $get('source') === 'satellite'),
                FileUpload::make('region')
                    ->maxSize(51200)
                    ->preserveFilenames()
                    ->previewable(false)
                    ->label("Masukan Region of Interest (ROI)")
                    ->hint("Upload a GeoJSON")
                    ->hidden(fn (Get $get) => $get('source') === 'drone')
                    ->required(fn (Get $get) => $get('source') === 'satellite'),
                DateTimePicker::make('start_date')
                    ->format('d-m-Y')
                    ->weekStartsOnMonday()
                    ->timezone('Asia/Jakarta')
                    ->maxDate(now())
                    ->label("Start Date")
                    ->hidden(fn (Get $get) => $get('source') === 'drone')
                    ->required(fn (Get $get) => $get('source') === 'satellite'),
                DateTimePicker::make('end_date')
                    ->format('d-m-Y')
                    ->weekStartsOnMonday()
                    ->timezone('Asia/Jakarta')
                    ->maxDate(now())
                    ->label("End Date")
                    ->hidden(fn (Get $get) => $get('source') === 'drone')
                    ->required(fn (Get $get) => $get('source') === 'satellite'),
                FileUpload::make('path')
                    ->maxSize(51200)
                    ->preserveFilenames()
                    ->previewable(false)
                    ->label("Masukan File Tiff")
                    ->hidden(fn (Get $get) => $get('source') === 'satellite')
                    ->required(fn (Get $get) => $get('source') === 'drone'),
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
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->before(function (Model $record) {
                        try {
                            RasterJob::dispatch($record,null,true);
                        } catch (Exception $e) {
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
            'index' => Pages\ListRasters::route('/'),
            'create' => Pages\CreateRaster::route('/create'),
            'view' => Pages\ViewRaster::route('/{record}'),
            'edit' => Pages\EditRaster::route('/{record}/edit'),
        ];
    }
}
