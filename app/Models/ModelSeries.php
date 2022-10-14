<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelSeries extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "modelseries";

    public function carBody()
    {
        return $this->hasOne(CarBody::class);
    }

}
