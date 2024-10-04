<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Jobs\ProcessFileJob;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\VectorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVector extends CreateRecord
{
    protected static string $resource = VectorResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $file_name = $data["path"];
        $file_path = asset("storage/{$file_name[0]}");
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        $file_content = file_get_contents($file_path, false, $context);
        $data["geojson"] = json_encode(json_decode($file_content, True));

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the record
        $record = static::getModel()::create($data);

        // Dispatch background job to process the file
        ProcessFileJob::dispatch($record);

        // Return the created record
        return $record;
    }
}
