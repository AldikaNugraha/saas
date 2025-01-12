<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Numerical extends Model
{
    use HasFactory;
    protected $fillable = [
        'categorical_id', 'type_value', 'name', 'type_value'
    ];

    public function categorical()
    {
        return $this->belongsTo(Categorical::class);
    }
}
