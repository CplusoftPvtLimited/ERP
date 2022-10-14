<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = "articles";
    protected $guarded = [];

    public function articleCriteria(){
        return $this->hasOne(ArticleCriteria::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleLink(){
        return $this->hasOne(ArticleLinks::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleDocs() {
        return $this->hasOne(ArticleDocs::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleCrosses(){
        return $this->hasOne(ArticleCross::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleEAN(){
        return $this->hasOne(ArticleEAN::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleMain(){
        return $this->hasOne(ArticleMain::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleVehicleTree(){
        return $this->hasOne(ArticleVehicleTree::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleText(){
        return $this->hasOne(ArticleText::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function genericArticles()
    {
        return $this->hasMany(GenericArticle::class);
    }

    public function brand() {   // usefull
        return $this->belongsTo(Ambrand::class,'dataSupplierId', 'brandId');
    }

    public function brands(){
        return $this->hasMany(Ambrand::class,'brandId','dataSupplierId');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'mfrId', 'manuId');
    }

    public function assemblyGroup()
    {
        return $this->belongsTo(AssemblyGroupNode::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }
    public function section() { 
        return $this->belongsTo(AssemblyGroupNode::class,'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }

}
