<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSeries extends Model
{
    use HasFactory;

    protected $table = "modelseries";

    public function carBody()
    {
        return $this->hasOne(CarBody::class);
    }

}
