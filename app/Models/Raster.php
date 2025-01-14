<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raster extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 
        'source', 
        'name', 
        'crs', 
        'gsd', 
        'num_bands', 
        'north', 
        'south', 
        'east', 
        'west'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
