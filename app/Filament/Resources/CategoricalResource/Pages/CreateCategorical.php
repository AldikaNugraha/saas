<?php

namespace App\Filament\Resources\CategoricalResource\Pages;

use App\Filament\Resources\CategoricalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreateCategorical extends CreateRecord
{
    protected static string $resource = CategoricalResource::class;
    protected string $file_path;
    public function read_csv_file(){

    }
    public function add_new_column(){
        
    }
    protected function mutateFormDataBeforeCreate(array $data): array {
        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        return $record;
    }
}
