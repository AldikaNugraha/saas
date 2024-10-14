<?php

namespace App\Filament\Resources\RasterResource\Pages;

use App\Filament\Resources\RasterResource;
use App\Jobs\RasterJob;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRaster extends EditRecord
{
    protected static string $resource = RasterResource::class;
    protected string $file_path;
    protected function mutateFormDataBeforeSave(array $data): array {
        $file_name = $data["path"];
        $this->file_path = storage_path("app/public/{$file_name}");
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        unset($data['path']);
        $record->update($data);
        RasterJob::dispatch($record, $this->file_path,false);
        
        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
