<?php

namespace App\Filament\Resources\CategoricalResource\Pages;

use App\Filament\Imports\CategoricalImporter;
use App\Filament\Resources\CategoricalResource;
use App\Filament\Resources\CategoricalResource\Widgets\CustomChart;
use App\Filament\Widgets\CustomChart2;
use App\Models\Categorical;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListCategoricals extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = CategoricalResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CategoricalResource\Widgets\NumericalOverview::class,
            CustomChart::class,
        ];
    }
}
