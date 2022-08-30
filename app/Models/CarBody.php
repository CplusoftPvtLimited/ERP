<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBody extends Model
{
    use HasFactory;

    protected $table = "carsbodies";


    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function modelseries()
    {
        return $this->belongsTo(ModelSeries::class);
    }
}
