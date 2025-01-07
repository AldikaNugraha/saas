<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorical extends Model
{
    use HasFactory;
    protected $fillable = [
        'vector_id', 'name', 'pj_blok','area',
        'num_tree','is_research', 'is_panen','is_pupuk'
    ];

    
    public function vector()
    {
        return $this->belongsTo(Vector::class);
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
