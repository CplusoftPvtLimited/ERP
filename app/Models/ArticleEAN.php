<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleEAN extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "articleean";
    protected $guarded = [];

}
