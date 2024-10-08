<?php

namespace App\Filament\Resources\RasterResource\Pages;

use App\Filament\Resources\RasterResource;
use App\Jobs\RasterJob;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRaster extends CreateRecord
{
    protected static string $resource = RasterResource::class;
    // protected static string $view = 'filament.pages.create-raster-page';
    protected function mutateFormDataBeforeCreate(array $data): array {

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        RasterJob::dispatch($record);

        return $record;
    }
}
