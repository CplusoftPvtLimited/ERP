<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericArticleGroup extends Model
{
    use HasFactory;

    protected $table = "genericarticlesgroups";

    public function genericArticle()
    {
        return $this->belongsTo(GenericArticle::class);
    }

}
