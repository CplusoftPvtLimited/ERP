<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ambrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ambrand";

    public function ambrandsaddress(){
        return $this->hasOne(AmbrandAddress::class);
    }

    public function article() {
        return $this->hasOne(Article::class, 'dataSupplierId', 'brandId');
    }
     
}
