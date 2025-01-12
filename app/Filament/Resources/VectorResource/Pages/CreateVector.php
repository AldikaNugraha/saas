<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Jobs\GeojsonJob;
use App\Jobs\ProcessCategoricalProperties;
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
        // [
        //     "project_id" => "1"
        //     "name" => "13"
        //     "path" => "predicted_locations_polygon_ndvi_4326.geojson"
        //     "categorical_properties" => array:5 [▼
        //         0 => "latt"
        //         1 => "long"
        //         2 => "block"
        //         3 => "isResearch"
        //         4 => "area"
        //     ]
        //     "numerical_properties" => array:1 [▼
        //         0 => "_mean"
        //     ]
        // ]
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
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        unset($data['path'], $data['categorical_properties'], $data['numerical_properties']);
        $record = static::getModel()::create($data);
        
        ProcessCategoricalProperties::dispatch($record->id, $this->categorical_properties, $this->geojson_response);
        GeojsonJob::dispatch($record, $this->geojson_response, is_delete:false);
        Storage::delete($this->file_path);

        return $record;
    }
}
