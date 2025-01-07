<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class GeoJson implements Rule
{
    public function passes($attribute, $value)
    {
        try {
            // Read the uploaded file
            $content = file_get_contents($value->getRealPath());
            $data = json_decode($content, true);
            // Check if the JSON was parsed correctly
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("GeoJSON validation failed: Invalid JSON format");
                return false;
            }

            // Check if the `type` key exists
            if (!isset($data['type'])) {
                Log::error("GeoJSON validation failed: 'type' key is missing");
                return false;
            }

            $type = $data['type'];

            // If the type is "Feature", validate its structure
            if ($type === 'Feature') {
                if (!isset($data['geometry'], $data['properties'], $data['crs'])) {
                    Log::error("GeoJSON validation failed: 'Feature' type must have 'geometry', 'properties', and 'crs' keys");
                    return false;
                }
            }
            // If the type is "FeatureCollection", validate its structure
            elseif ($type === 'FeatureCollection') {
                if (!isset($data['features'], $data['crs'])) {
                    Log::error("GeoJSON validation failed: 'FeatureCollection' type must have 'features' and 'crs' keys");
                    return false;
                }
                // Ensure features is an array
                if (!is_array($data['features'])) {
                    Log::error("GeoJSON validation failed: 'features' must be an array");
                    return false;
                }
            }
            // For other types, ensure it's one of the specified geometry types
            else {
                $validGeometryTypes = [
                    'Point',
                    'LineString',
                    'Polygon',
                    'MultiPoint',
                    'MultiLineString',
                    'MultiPolygon'
                ];

                if (!in_array($type, $validGeometryTypes, true)) {
                    Log::error("GeoJSON validation failed: Invalid geometry type '{$type}'");
                    return false;
                }
            }

            // Passed all validation checks
            return true;
        } catch (\Exception $e) {
            Log::error("GeoJSON validation failed: Exception occurred - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The uploaded file must be a valid GeoJSON.';
    }

}
