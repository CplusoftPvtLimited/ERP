<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbrandAddress extends Model
{
    use HasFactory;
    protected $table = "ambrandsaddress";

    public function ambrand(){
        return $this->hasOne(Ambrand::class);
    }

}
