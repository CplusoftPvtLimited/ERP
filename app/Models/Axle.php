<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Axle extends Model
{
    use HasFactory;

    protected $table = "axles";

    public function axleDetail(){
        return $this->hasOne(AxleDetail::class);
    }

    public function axleBodyType(){
        return $this->hasOne(AxleBodyType::class);
    }

    public function axleBrakeSize(){
        return $this->hasOne(AxleBrakeSize::class);
    }
}
