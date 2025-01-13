<?php

namespace App\Filament\Resources\DiffnumericalResource\Pages;

use App\Filament\Resources\DiffnumericalResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreateDiffnumerical extends CreateRecord
{
    protected static string $resource = DiffnumericalResource::class;
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
