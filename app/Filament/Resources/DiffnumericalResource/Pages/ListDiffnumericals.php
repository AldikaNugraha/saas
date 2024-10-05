<?php

namespace App\Filament\Resources\DiffnumericalResource\Pages;

use App\Filament\Resources\DiffnumericalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiffnumericals extends ListRecords
{
    protected static string $resource = DiffnumericalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
