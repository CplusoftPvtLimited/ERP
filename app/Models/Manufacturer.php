<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $table = "manufacturers";

    public function carBody()
    {
        return $this->hasOne(CarBody::class);
    }

    public function articleCrosses(){
        $this->belongsTo(ArticleCross::class,'mfrId','manuId');
    }

}
