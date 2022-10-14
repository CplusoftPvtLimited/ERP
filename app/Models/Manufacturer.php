<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use HasFactory ,SoftDeletes;

    protected $table = "manufacturers";

    public function carBody()
    {
        return $this->hasOne(CarBody::class);
    }

    public function modelSeries()
    {
        return $this->hasMany(ModelSeries::class, 'manuId','manuId');
    }

}
