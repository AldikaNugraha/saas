<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diffnumerical extends Model
{
    use HasFactory;
    protected $fillable = [
        'categorical_id', 'type_value', 'day', 'month', 'year', "created_at"
    ];

    public function categorical()
    {
        return $this->belongsTo(Categorical::class);
    }
    
    public function numericalType() 
    {
        return $this->hasMany(NumericalType::class, "diffnumerical_id");
    }
    
}
