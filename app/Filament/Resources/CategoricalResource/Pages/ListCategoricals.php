<?php

namespace App\Filament\Resources\CategoricalResource\Pages;

use App\Filament\Resources\CategoricalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;


class ListCategoricals extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = CategoricalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CategoricalResource\Widgets\NumericalOverview::class,
        ];
    }
}
