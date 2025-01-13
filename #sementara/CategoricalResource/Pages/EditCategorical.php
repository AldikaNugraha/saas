<?php

namespace App\Filament\Resources\CategoricalResource\Pages;

use App\Filament\Resources\CategoricalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategorical extends EditRecord
{
    protected static string $resource = CategoricalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
