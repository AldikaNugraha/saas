<?php

namespace App\Jobs;

use App\Models\Categorical;
use App\Models\Numerical;
use App\Models\Vector;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ProcessVectorFeatures implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $vector_id;
    protected $vector_name;
    protected $geojson;
    protected $categorical_properties;
    protected $numerical_properties;

    public function __construct(Vector $vector, $categorical_properties, $numerical_properties, $geojson_response)
    {
        $this->vector_id = $vector->id;
        $this->vector_name = $vector->name;
        $this->geojson = json_decode($geojson_response, true);
        $this->categorical_properties = $categorical_properties;
        $this->numerical_properties = $numerical_properties;
    }

    public function handle(): void
    {
        if (isset($this->geojson['type']) && $this->geojson['type'] === 'FeatureCollection') {
            $features = $this->geojson['features'] ?? [];
        }
        $total_features = count($features);
        $batch_size = 50;
        
        try {
            // Process in batches of 50
            for ($start = 0; $start < $total_features; $start += $batch_size) {
                $batch = array_slice($features, $start, $batch_size);
                
                // Create a Categorical record for each feature
                foreach ($batch as $feature) {
                    $properties = $feature['properties'] ?? [];

                    // Get property keys as array
                    $property_keys = array_keys($properties);
                    
                    // Filter categorical properties by using the indexes
                    $filtered_categorical_properties = [];
                    foreach ($this->categorical_properties as $index) {
                        if (isset($property_keys[$index])) {
                            $key = $property_keys[$index];
                            $filtered_categorical_properties[$key] = $properties[$key];
                        }
                    }
                    $new_categorical = Categorical::create([
                        "name" => "Feature ". $this->vector_id,
                        "vector_id" => $this->vector_id,
                        "columns" => json_encode($filtered_categorical_properties), 
                    ]);

                    // $last = Categorical::latest()->first();
                    $filtered_numerical_properties = [];
                    foreach ($this->numerical_properties as $index) {
                        if (isset($property_keys[$index])) {
                            $key = $property_keys[$index];
                            $filtered_numerical_properties[$key] = $properties[$key];
                        }
                    }

                    foreach ($filtered_numerical_properties as $type => $value) {
                        Numerical::create([
                            'categorical_id' => $new_categorical->id,
                            'name' => "Numerical " . $new_categorical->id,
                            'type' => $type,
                            'type_value' => $value
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error in ProcessVectorFeatures Job: ' . $th->getMessage(), [
                'vector_id' => $this->vector_id,
                'categorical_properties' => $this->categorical_properties,
                'geojson' => $this->geojson,
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }
}