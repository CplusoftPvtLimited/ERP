<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = "cars";


    public function bodyMarkCardId()
    {
        return $this->hasMany(BodyMarkCarID::class);
    }


    public function bodyMark()
    {
        return $this->belongsToMany(BodyMark::class, 'bodymarkcarids')->withPivot('term')->withTimestamps();
    }


    public function carBody()
    {
        return $this->hasOne(CarBody::class);
    }
}
