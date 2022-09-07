<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambrand extends Model
{
    use HasFactory;

    protected $table = "ambrand";

    public function ambrandsaddress(){
        return $this->hasOne(AmbrandAddress::class);
    }

    public function article(){
        return $this->hasOne(Article::class, 'dataSupplierId', 'brandId');
    }
}
