<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorical extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'name'
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
}
