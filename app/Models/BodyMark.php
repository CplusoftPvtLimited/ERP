<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyMark extends Model
{
    use HasFactory;

    protected $table = "bodymark";

    public function bodyMarkCardId()
    {
        return $this->hasMany(BodyMarkCarID::class);
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'bodymarkcarids')->withPivot('term')->withTimestamps();
    }
}
