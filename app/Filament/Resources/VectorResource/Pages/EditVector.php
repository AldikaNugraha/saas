<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVector extends EditRecord
{
    protected static string $resource = VectorResource::class;

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
