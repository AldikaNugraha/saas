<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use App\Jobs\GeojsonJob;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditVector extends EditRecord
{
    protected static string $resource = VectorResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $file_name = $data["path"];
        $this->file_path = asset("storage/{$file_name}");
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        $file_content = file_get_contents($this->file_path, false, $context);
        $this->geojson_response = json_encode(json_decode($file_content, True));
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        unset($data['path']);
        $record->update($data);
        GeojsonJob::dispatch($record, $this->geojson_response, is_delete:false);
        
        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $data['geojson'] = json_encode($data["geojson"]);
    
        return $data;
    }
}
