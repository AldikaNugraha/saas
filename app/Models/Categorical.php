<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorical extends Model
{
    use HasFactory;
    protected $fillable = [
        'vector_id', 'name', 'columns'
    ];

    protected $cast = [
        'columns' => 'array'
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
