<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleEAN extends Model
{
    use HasFactory;

    protected $table = "articleean";
    protected $guarded = [];

}
