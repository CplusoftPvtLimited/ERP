<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericArticle extends Model
{
    use HasFactory;

    protected $table = "genericarticles";

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function assemblyGroupNode()
    {
        return $this->belongsTo(AssemblyGroupNode::class);
    }

    public function genericArticleGroup()
    {
        return $this->hasOne(GenericArticleGroup::class);
    }

}
