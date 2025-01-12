<?php

namespace App\Jobs;

use App\Models\Categorical;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
function getBatchProperties(array $batch): array
    {
        $columns_data = [];

        foreach ($batch as $feature) {
            $properties = $feature['properties'] ?? [];
            foreach ($properties as $key => $value) {
                $columns_data[$key][] = $value; // Collect all values for each key
            }
        }

        return $columns_data;
    }
class ProcessCategoricalProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $vector_id;
    protected $geojson;
    protected $categorical_properties;

    public function __construct($vector_id, $categorical_properties, $geojson_response)
    {
        $this->vector_id = $vector_id;
        $this->geojson = json_decode($geojson_response, true);
        $this->categorical_properties = $categorical_properties;
    }

    public function handle(): void
    {
        if (isset($this->geojson['type']) && $this->geojson['type'] === 'FeatureCollection') {
            $features = $this->geojson['features'] ?? [];
        }
        $total_features = count($features);
        $columns_data = [];
        $batch_size = 50;
        
        try {
            // Process in batches of 50
            for ($start = 0; $start < $total_features; $start += $batch_size) {
                $batch = array_slice($features, $start, $batch_size);
                $columns_data = getBatchProperties($batch);

                // Save to the database
                Categorical::create([
                    "name" => $this->vector_id,
                    "vector_id" => $this->vector_id,
                    "columns" => json_encode($columns_data), // Save as JSON
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error in ProcessCategoricalProperties Job: ' . $th->getMessage(), [
                'vector_id' => $this->vector_id,
                'categorical_properties' => $this->categorical_properties,
                'geojson' => $this->geojson,
                'trace' => $th->getTraceAsString(),
            ]);
            
        }
    }
}
