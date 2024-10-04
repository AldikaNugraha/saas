<?php

namespace App\Filament\Resources\RasterResource\Pages;

use App\Filament\Resources\RasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRaster extends EditRecord
{
    protected static string $resource = RasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
