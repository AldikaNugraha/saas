<?php

namespace App\Filament\Resources\DiffnumericalResource\Pages;

use App\Filament\Resources\DiffnumericalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiffnumerical extends EditRecord
{
    protected static string $resource = DiffnumericalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
