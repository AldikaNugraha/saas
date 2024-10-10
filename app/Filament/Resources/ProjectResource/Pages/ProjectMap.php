<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use GuzzleHttp\Client;

function sendOverlayRequest( $project_id= null, $raster_ids= null, $vector_ids= null)
{
    $client = new Client();

    $client->post('http://127.0.0.1:5001/getMapAttr', [
        'json' => [
            'raster_ids' => $raster_ids,
            'project_id' => $project_id,
            'vector_ids' => $vector_ids,
        ],
    ]);
}

function getVector(Project $projectRecord) {
    $categoricals = $projectRecord->categoricals()->with('vectors')->get();
    return $categoricals
        ->pluck('vectors.*.id')
        ->flatten()
        ->all();
}

function getRaster(Project $projectRecord) {
    // Retrieve all related rasters for the project and pluck their IDs
    $rasterIds = $projectRecord->raster()->pluck('id');

    return $rasterIds;
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
        $this->project_raster = getRaster($this->record);
        $this->project_id = $this->record->id;
        // dd(
        // "Geojson: ". $this->project_vector,
        //     "Memory Taken: ". round(memory_get_peak_usage() / (1024 * 1024), 2) . "MB",
        //     "Time Taken: ". round(microtime(2)- LARAVEL_START, 2). "sec",
        // );

        sendOverlayRequest($this->project_id, $this->project_raster, $this->project_vector);
    }
}
