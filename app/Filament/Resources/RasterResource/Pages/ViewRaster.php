<?php

namespace App\Filament\Resources\RasterResource\Pages;

use App\Filament\Resources\RasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRaster extends ViewRecord
{
    protected static string $resource = RasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
