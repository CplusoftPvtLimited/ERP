<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCross extends Model
{
    use HasFactory;
    protected $table = "articlecrosses";

    public function articles(){
        $this->belongsTo(Article::class,'legacyArticleId','legacyArticleId');
    }

}
