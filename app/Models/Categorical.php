<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorical extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'name', 'pj_blok','area',
        'num_tree','is_research', 'is_panen','is_pupuk'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vectors()
    {
        return $this->hasMany(Vector::class);
    }

    public function numerical()
    {
        return $this->hasMany(Numerical::class, "categorical_id");
    }

    public function diffnumerical()
    {
        return $this->hasMany(Diffnumerical::class, "categorical_id");
    }
}
