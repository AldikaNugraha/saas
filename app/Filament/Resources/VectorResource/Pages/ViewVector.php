<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVector extends ViewRecord
{
    protected static string $resource = VectorResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $data['geojson'] = json_encode($data["geojson"]);
    
        return $data;
    }
}
