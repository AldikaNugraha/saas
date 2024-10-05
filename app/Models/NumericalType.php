<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumericalType extends Model
{
    use HasFactory;
    protected $table = "numerical_type";
    protected $fillable = [
        'diffnumerical_id',
        'type_id'
    ];
    public function diffnumerical() {
        return $this->belongsTo(Diffnumerical::class, "diffnumerical_id");
    }
    public function type() {
        return $this->belongsTo(Type::class, "type_id");
    }
}
