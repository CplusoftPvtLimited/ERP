<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleLinks extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "articlelinks";
    protected $guarded = [];

}
