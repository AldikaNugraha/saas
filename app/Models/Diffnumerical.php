<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diffnumerical extends Model
{
    use HasFactory;
    protected $fillable = [
        'categorical_id', 'num_field', 'day', 'month', 'year'
    ];

    public function categorical()
    {
        return $this->belongsTo(Categorical::class);
    }
    
    public function types()
    {
        return $this->belongsToMany(Type::class);
    }
}
