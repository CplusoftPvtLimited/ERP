<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AxleBodyType extends Model
{
    use HasFactory;

    protected $table = "axlebodytype";

    public function axleDetail(){
        return $this->hasOne(AxleDetail::class);
    }
}
