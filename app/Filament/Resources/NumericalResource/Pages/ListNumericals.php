<?php

namespace App\Filament\Resources\NumericalResource\Pages;

use App\Filament\Resources\NumericalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNumericals extends ListRecords
{
    protected static string $resource = NumericalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
