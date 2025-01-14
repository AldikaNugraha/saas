<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Jobs\GeojsonJob;
use App\Jobs\ProcessVectorFeatures;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\VectorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Log;

class CreateVector extends CreateRecord
{
    protected static string $resource = VectorResource::class;
    protected string $file_path;
    protected string $geojson_response;
    protected $categorical_properties;
    protected function mutateFormDataBeforeCreate(array $data): array {
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
        $this->categorical_properties = $data["categorical_properties"];
        $this->numerical_properties = $data["numerical_properties"];
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        unset($data['path'], $data['categorical_properties'], $data['numerical_properties']);
        $record = static::getModel()::create($data);
        
        ProcessVectorFeatures::dispatch(
            $record,
            $this->categorical_properties,
            $this->numerical_properties,
            $this->geojson_response
        );
        GeojsonJob::dispatch($record, $this->geojson_response, is_delete:false);
        Storage::delete($this->file_path);

        return $record;
    }
}
