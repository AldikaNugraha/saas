<?php

namespace App\Filament\Resources\NumericalResource\Pages;

use App\Filament\Resources\NumericalResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreateNumerical extends CreateRecord
{
    protected static string $resource = NumericalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        list($day, $month, $year) = explode('-', $data['created_at']);
        $data['day'] = $day;
        $data['month'] = $month;
        $data['year'] = $year;
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        return $record;
    }
}
