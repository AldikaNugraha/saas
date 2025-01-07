<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'name', 'description', 'num_vectors', 'num_rasters', 'commodity', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function raster()
    {
        return $this->hasMany(Raster::class);
    }

    public function vector()
    {
        return $this->hasMany(Vector::class);
    }
}
