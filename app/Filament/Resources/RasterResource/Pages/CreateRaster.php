<?php

namespace App\Filament\Resources\RasterResource\Pages;

use App\Filament\Resources\RasterResource;
use App\Jobs\RasterJob;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;

class CreateRaster extends CreateRecord
{
    protected static string $resource = RasterResource::class;
    // protected static string $view = 'filament.pages.create-raster-page';
    protected string $file_path;
    protected array $satellite_data;
    protected string $region_geojson;
    protected function mutateFormDataBeforeCreate(array $respone_data): array {
//  [
//   "project_id" => "1"
//   "name" => "COba 1"
//   "source" => "satellite"
//   "collection_name" => "LANDSAT/LC09/C02/T2_L2"
//   "do_monitoring" => true
//   "region" => "blok_1_polygon.geojson"
//   "start_date" => "01-11-2020"
//   "end_date" => "31-10-2024"
//  ]
        if ($respone_data["source"] == "satellite") {
            $file_name = $respone_data["region"];
            $this->file_path = asset("storage/{$file_name}");
            $context = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
            ]);
            $file_content = file_get_contents($this->file_path, false, $context);
            $this->region_geojson = json_encode(json_decode($file_content, True));

            $this->satellite_data = array(
                "project_id" => $respone_data["project_id"],
                "name"=> $respone_data["name"],
                "source"=> $respone_data["source"],
                "sattelite_source"=> $respone_data["sattelite_source"],
                "do_monitoring"=> $respone_data["do_monitoring"],
                "region"=> $this->region_geojson,
                "start_date"=>$respone_data["start_date"],
                "end_date"=>$respone_data["end_date"],
            );
        }
        if ($respone_data["source"] == "drone") {
            $file_name = $respone_data["path"];
            $this->file_path = storage_path("app/public/{$file_name}");
        }
        return $respone_data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        if ($data["source"] == "drone") {
            unset($data['path']);
        }

        if ($data["source"] == "satellite") {
            unset($data['sattelite_source']);
            unset($data['do_monitoring']);
            unset($data['region']);
            unset($data['start_date']);
            unset($data['end_date']);
        }
        $record = static::getModel()::create($data);

        // RasterJob::dispatch($record, $this->file_path,false);
        return $record;
    }
}
