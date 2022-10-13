<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCriteria extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "articlecriteria";
    protected $guarded = [];

}
