<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVectors extends ListRecords
{
    protected static string $resource = VectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
