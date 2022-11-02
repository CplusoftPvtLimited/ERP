<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ambrand extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "ambrand";
    protected $fillable = [
        'brandId',
        'brandLogoID',
        'brandName',
        'lang',
        'articleCountry'
    ];
}
