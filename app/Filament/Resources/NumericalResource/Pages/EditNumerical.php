<?php

namespace App\Filament\Resources\NumericalResource\Pages;

use App\Filament\Resources\NumericalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNumerical extends EditRecord
{
    protected static string $resource = NumericalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
