<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Raster;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use GuzzleHttp\Client;

function sendOverlayRequest(Raster $imageRecord, $vectorRecord = null )
{
    $client = new Client();

    $client->post('http://127.0.0.1:5001/getMapAttr', [
        'json' => [
            'id' => $imageRecord->project_id,
            'geojson' => $vectorRecord,
        ],
    ]);
}

function getVector(Project $projectRecord){
    // Clone the query to avoid modifying the original
    $query = $projectRecord->categoricals()->with('vectors');
    
    // Eager load the first related categorical and its vector
    $categorical = $query->first();
    
    if ($categorical) {
        $categorical_vector = $categorical->vectors->first();
        return $categorical_vector ? $categorical_vector->geojson : null;
    }

    return null;
}

class ProjectMap extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.project-map';

    use InteractsWithRecord;
    
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->project_vector = getVector($this->record);
        // dd($this->project_vector);
        // dd(
        // "Geojson: ". $this->project_vector,
        //     "Memory Taken: ". round(memory_get_peak_usage() / (1024 * 1024), 2) . "MB",
        //     "Time Taken: ". round(microtime(2)- LARAVEL_START, 2). "sec",
        // );

        $this->project_raster = $this->record->raster;
        if ($this->project_raster->first() !== null) {
            sendOverlayRequest($this->project_raster->first(), $this->project_vector);
        }
    }


}
