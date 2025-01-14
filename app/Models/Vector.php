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
        'project_id',
        'name',
        'type',
        'crs',
        'num_features',
        'area',
    ];

    public function categorical()
    {
        return $this->hasMany(Categorical::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
