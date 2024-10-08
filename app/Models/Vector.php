<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;

class Vector extends Model
{
    use HasFactory;
    // use HasPostgisColumns;
    // protected $postgisFields = [
    //     'geojson',
    // ];
    // protected array $postgisColumns = [
    //     'geojson' => [
    //         'type' => 'geometry',
    //         'srid' => 4326,
    //     ],
    // ];

    protected $fillable = [
        'categorical_id', 'type', 'num_features', 'area', 'name'
    ];

    public function categorical()
    {
        return $this->belongsTo(Categorical::class);
    }
}
