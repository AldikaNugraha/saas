<?php

namespace App\Filament\Resources\RasterResource\Pages;

use App\Filament\Resources\RasterResource;
use App\Jobs\RasterJob;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreateRaster extends CreateRecord
{
    protected static string $resource = RasterResource::class;
    // protected static string $view = 'filament.pages.create-raster-page';
    protected string $file_path;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $file_name = $data["path"];
        $this->file_path = storage_path("app/public/{$file_name}");
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Storage::delete("public/{$data['path']}");
        unset($data['path']);
        $record = static::getModel()::create($data);

        RasterJob::dispatch($record, $this->file_path);
        return $record;
    }
}
