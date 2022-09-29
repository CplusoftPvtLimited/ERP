<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmBrand extends Model
{
    use HasFactory;
    protected $table = "ambrand";
    protected $fillable = [
        'brandId',
        'brandLogoID',
        'brandName',
        'lang',
        'articleCountry'
    ];

}
